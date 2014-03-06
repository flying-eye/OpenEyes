<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class OECommandBuilder extends CDbCommandBuilder
{
	public function createInsertFromTableCommand($table_version,$table,$criteria,$deleted_transaction_id=null)
	{
		$this->ensureTable($table);
		$this->ensureTable($table_version);

		$columns = array();
		$columns = array();

		foreach (array_keys($table_version->columns) as $column) {
			if (!in_array($column,array('version_date','version_id','deleted_transaction_id'))) {
				$columns[] = $column;
			}
		}

		$sql="INSERT INTO {$table_version->rawName} (`".implode("`,`",$columns)."`,`version_date`,`version_id`,`deleted_transaction_id`) SELECT `".implode("`,`",$columns)."`, :oevalue1, :oevalue2, :oevalue3 FROM {$table->rawName}";

		$sql=$this->applyJoin($sql,$criteria->join);
		$sql=$this->applyCondition($sql,$criteria->condition);
		$sql=$this->applyOrder($sql,$criteria->order);
		$sql=$this->applyLimit($sql,$criteria->limit,$criteria->offset);

		$command=$this->getDbConnection()->createCommand($sql);

		$command->bindValue(':oevalue1',date('Y-m-d H:i:s'));
		$command->bindValue(':oevalue2',null);
		$command->bindValue(':oevalue3',$deleted_transaction_id);

		$this->bindValues($command,$criteria->params);

		return $command;
	}

	/**
	 * Creates a DELETE command.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @param CDbCriteria $criteria the query criteria
	 * @return CDbCommand delete command.
	 */
	public function createDeleteCommand($table,$criteria)
	{
		$this->ensureTable($table);

		if (!$table_version = $this->getDbConnection()->getSchema()->getTable("{$table->name}_version")) {
			throw new Exception("Missing version table: {$table->name}_version");
		}

		if ($this->getDbConnection()->createCommand()
			->select("*")
			->from($table->name)
			->where($criteria->condition, $criteria->params)
			->limit(1)
			->queryRow()) {

			$command = $this->createInsertFromTableCommand($table_version,$table,$criteria,Yii::app()->db->transaction->id);
			if (!$command->execute()) {
				throw new Exception("Unable to insert version row: ".print_r($command,true));
			}
		}

		$sql="DELETE FROM {$table->rawName}";
		$sql=$this->applyJoin($sql,$criteria->join);
		$sql=$this->applyCondition($sql,$criteria->condition);
		$sql=$this->applyGroup($sql,$criteria->group);
		$sql=$this->applyHaving($sql,$criteria->having);
		$sql=$this->applyOrder($sql,$criteria->order);
		$sql=$this->applyLimit($sql,$criteria->limit,$criteria->offset);
		$command=$this->getDbConnection()->createCommand($sql);
		$this->bindValues($command,$criteria->params);
		return $command;
	}
}