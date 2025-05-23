<?php
/**
 * @package       SW JProjects
 * @version       2.4.0.1
 * @Author        Sergey Tolkachyov
 * @copyright     Copyright (c) 2018 - 2025 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       https://web-tolk.ru
 * @since         1.0.0
 */

namespace Joomla\Component\SWJProjects\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\Component\SWJProjects\Administrator\Helper\TranslationHelper;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use function defined;
use function file_get_contents;
use function implode;
use function is_file;
use function md5;
use function serialize;
use function str_replace;

class DocumentModel extends AdminModel
{
	/**
	 * Project object.
	 *
	 * @var  object
	 *
	 * @since  1.4.0
	 */
	protected $_project = null;

	/**
	 * Method to get project data.
	 *
	 * @param   integer  $pk  The id of the project.
	 *
	 * @return  mixed  Project object on success, false on failure.
	 *
	 * @since  1.4.0
	 */
	public function getProject($pk = null)
	{
		if (empty($pk)) return false;

		if ($this->_project === null)
		{
			$this->_project = array();
		}

		if (!isset($this->_project[$pk]))
		{
			$model = Factory::getApplication()
				->bootComponent('com_swjprojects')
				->getMVCFactory()->createModel('Project', 'Administrator', ['ignore_request' => true]);

			$project = $model->getItem($pk);

			$this->_project[$pk] = ($project->id !== null) ? $project : false;
		}

		return $this->_project[$pk];
	}

	/**
	 * Method to get project data.
	 *
	 * @param   integer  $pk  The id of the project.
	 *
	 * @return  mixed  Project object on success, false on failure.
	 *
	 * @since  1.4.0
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the params field value to array
			$registry     = new Registry($item->params);
			$item->params = $registry->toArray();

			// Default values
			$item->translates = array();

			// Set values
			if (!empty($item->id))
			{
				$db = $this->getDatabase();

				// Set translates
				$query = $db->getQuery(true)
					->select('*')
					->from('#__swjprojects_translate_documentation')
					->where('id = ' . $item->id);
				$db->setQuery($query);
				$item->translates = $db->loadObjectList('language');

				foreach ($item->translates as &$translate)
				{

					// Convert the metadata field value to array
					$registry            = new Registry($translate->metadata);
					$translate->metadata = $registry->toArray();
				}

			}
		}

		return $item;
	}

	/**
	 * Abstract method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	public function getForm($data = [], $loadData = true)
	{
		$form = $this->loadForm('com_swjprojects.document', 'document', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		// Get item id
		$id = (int) $this->getState('document.id', Factory::getApplication()->input->get('id', 0));

		// Modify the form based on Edit State access controls
		if ($id != 0 && !Factory::getApplication()->getIdentity()->authorise('core.edit.state', 'com_swjprojects.document.' . $id))
		{
			$form->setFieldAttribute('state', 'disabled', 'true');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param   Form    $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  array|boolean  Array of filtered data if valid, false otherwise.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	public function validate($form, $data, $group = null)
	{
		$translates = (!empty($data['translates'])) ? $data['translates'] : array();

		// Main validate
		if (!$data = parent::validate($form, $data, $group))
		{
			return $data;
		}

		// Translates validate
		$forms = $this->getTranslateForms(false);

		$data['translates'] = [];
		foreach ($forms as $code => $form)
		{
			$translate = (!empty($translates[$code])) ? $translates[$code] : array();

			if (!$validate = parent::validate($form, $translate, $group))
			{
				return $validate;
			}

			$data['translates'][$code] = $validate;
		}

		return $data;
	}

	/**
	 * Method for getting the translate forms from the model.
	 *
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   boolean  $clear     Optional argument to force load a new forms.
	 *
	 * @return  array  Translates forms array on success, false on failure.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	public function getTranslateForms($loadData = true, $clear = false)
	{
		$translates = new Registry();

		// Get data
		if ($loadData)
		{
			$registry   = new Registry($this->loadFormData());
			$translates = new Registry($registry->get('translates'));
		}

		$forms = [];
		$name  = 'com_swjprojects.document';
		$file  = JPATH_COMPONENT . '/forms/translate_document.xml';
		if (!is_file($file))
		{
			throw new \RuntimeException('Could not load translate form file', 500);
		}

		foreach (TranslationHelper::getCodes() as $code)
		{
			$default = ($code == TranslationHelper::getDefault());
			$source  = $name . '_' . str_replace('-', '_', $code);
			$options = array('control' => 'jform[translates][' . $code . ']');

			// Create a signature hash
			$hash = md5($source . serialize($options));

			// Check if we can use a previously loaded form
			if (!$clear && isset($this->_forms[$hash]))
			{
				$forms[$code] = $this->_forms[$hash];

				continue;
			}

			$xml = file_get_contents($file);

			// Set required
			if ($default)
			{
				$xml = str_replace('translate_required', 'required', $xml);
			}

			// Replace translate code
			$xml = str_replace('[translate]', $code, $xml);

			// Load form
			if (!$form = Form::getInstance($source, $xml, $options)) continue;

			// Add fields
			Form::addFieldPath(JPATH_COMPONENT . '/models/fields');

			// Load data
			if ($loadData && !empty($translates->get($code)))
			{
				$formData = $translates->get($code);
			}
			else
			{
				$formData = [];
			}

			// Allow for additional modification of the form, and events to be triggered
			$this->preprocessForm($form, $formData);

			// Load the data into the form after the plugins have operated
			$form->bind($formData);

			$forms[$code]        = $form;
			$this->_forms[$hash] = $form;

		}

		return $forms;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	protected function loadFormData()
	{
		$data = Factory::getApplication()->getUserState('com_swjprojects.edit.document.data', array());
		if (empty($data))
		{
			$data = $this->getItem();
		}
		$this->preprocessData('com_swjprojects.document', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	public function save($data)
	{
		$pk    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();
		$isNew = true;

		// Load the row if saving an existing item
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Prepare alias field data
		$alias = (!empty($data['alias'])) ? $data['alias'] : $data['translates'][TranslationHelper::getDefault()]['title'];
		if (Factory::getContainer()->get('config')->get('unicodeslugs') == 1)
		{
			$alias = OutputFilter::stringURLUnicodeSlug($alias);
		}
		else
		{
			$alias = OutputFilter::stringURLSafe($alias);
		}
		if (empty(($alias))) $alias = Factory::getDate()->toUnix();

		// Check alias is already exist
		$checkAlias = $this->getTable();
		$checkAlias->load(array('alias' => $alias, 'project_id' => $data['project_id']));
		if (!empty($checkAlias->id) && ($checkAlias->id != $pk || $isNew))
		{
			$alias = $this->generateNewAlias($alias);
			Factory::getApplication()->enqueueMessage(Text::_('COM_SWJPROJECTS_ERROR_ALIAS_EXIST'), 'warning');
		}
		$data['alias'] = $alias;


		// Prepare params field data
		if (isset($data['params']))
		{
			$registry       = new Registry($data['params']);
			$data['params'] = $registry->toString('json', array('bitmask' => JSON_UNESCAPED_UNICODE));
		}

		if (parent::save($data))
		{
			$id = $this->getState($this->getName() . '.id');

			// Save translates
			$db    = $this->getDatabase();
			$query = $db->getQuery(true)
				->delete($db->quoteName('#__swjprojects_translate_documentation'))
				->where('id = ' . $id);
			$db->setQuery($query)->execute();

			foreach ($data['translates'] as $code => $translate)
			{
				// Prepare id field data
				$translate['id'] = $id;

				// Prepare language field data
				$translate['language'] = $code;

				// Prepare metadata field data
				if (isset($translate['metadata']))
				{
					$registry              = new Registry($translate['metadata']);
					$translate['metadata'] = $registry->toString('json', array('bitmask' => JSON_UNESCAPED_UNICODE));
				}

				$translate = (object) $translate;

				$db->insertObject('#__swjprojects_translate_documentation', $translate);
			}

			return $id;
		}

		return false;
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $name     The table type to instantiate
	 * @param   string  $prefix   A prefix for the table class name.
	 * @param   array   $options  Configuration array for model.
	 *
	 * @return  Table  A database object.
	 *
	 * @since  1.4.0
	 */
	public function getTable($name = 'Documentation', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to generate new alias if alias already exist.
	 *
	 * @param   string   $alias       The alias.
	 * @param   integer  $project_id  The project id.
	 *
	 * @return  string  Contains the modified alias.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	protected function generateNewAlias($alias, $project_id = null)
	{
		$table = $this->getTable();
		while ($table->load(array('alias' => $alias, 'project_id' => $project_id)))
		{
			$alias = StringHelper::increment($alias, 'dash');
		}

		return $alias;
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @throws  \Exception
	 *
	 * @since  1.4.0
	 */
	public function delete(&$pks)
	{
		$db = $this->getDatabase();

		if ($result = parent::delete($pks))
		{
			// Delete translates
			$query = $db->getQuery(true)
				->delete($db->quoteName('#__swjprojects_translate_documentation'))
				->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query)->execute();
		}

		return $result;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table  The Table object.
	 *
	 * @since  1.4.0
	 */
	protected function prepareTable($table)
	{
		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__swjprojects_projects'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
	}
}