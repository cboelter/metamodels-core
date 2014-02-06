<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Dca;

use DcGeneral\DC_General;
use MetaModels\Factory as ModelFactory;
use MetaModels\Helper\TableManipulation as MetaModelTableManipulation;


/**
 * This class is used from DCA tl_metamodel for various callbacks.
 *
 * @package    MetaModels
 * @subpackage Backend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class MetaModel extends \Backend
{
	/**
	 * Class constructor, imports the Backend user.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function getAttributes()
	{
		$objMetaModel = ModelFactory::byId();
		$tables = array();
		foreach(\Database::getInstance()->listTables() as $table)
		{
			$tables[$table]=$table;
		}
		return $tables;
	}

	/**
	 * list all index fields with type int from a table
	 *
	 * @param \DcGeneral\DC_General $dc
	 *
	 * @return array : string fieldname => string fieldname
	 */
	public function getTableKeys(DC_General $dc)
	{
		// TODO: unused currently.
		$result = array();
		$objTable = \Database::getInstance()->prepare("SELECT itemTable FROM tl_metamodel WHERE id=?")
			->limit(1)
			->execute($dc->id);
		if ($objTable->numRows > 0
			&& \Database::getInstance()->tableExists($objTable->itemTable, null, true))
		{
			$fields = \Database::getInstance()->listFields($objTable->itemTable);
			foreach($fields as $field)
			{
				if(array_key_exists('index', $field) && $field['type'] == 'int')
					$result[$field['name']] = $field['name'];
			}

		}
		return $result;
	}
}

