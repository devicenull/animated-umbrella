<?php
class BaseDBObject implements ArrayAccess
{
	// Name of the primary key
	const DB_KEY = '';
	// Name of the database table
	const DB_TABLE = '';
	// Full list of all DB fields (including primary key)
	const DB_FIELDS = [];
	// Fields that don't exist in the database, but can be generated at runtime
	var $virtual_fields = [];

	// last error message encountered
	var $error = '';

	var $record = [];

	public function __construct($params=[])
	{
		if (isset($params['record']))
		{
			$this->record = $params['record'];
		}
		else if (isset($params[static::DB_KEY]))
		{
			$this->construct_by_column(static::DB_KEY, $params[static::DB_KEY]);
		}
	}

	public function construct_by_column($column, $data): bool
	{
		global $db;
		if (!in_array($column, static::DB_FIELDS)) return false;

		$res = $db->Execute('select * from '.static::DB_TABLE.' where '.$column.'=?', [$data]);
		if ($res->RecordCount() == 1)
		{
			$this->record = $res->fields;
			return true;
		}

		return false;
	}

	public function isInitialized(): bool
	{
		return isset($this->record[static::DB_KEY]);
	}

	public function set(array $params): bool
	{
		global $db;
		$this->error = '';

		$update_fields = $update_values = [];
		foreach (static::DB_FIELDS as $field)
		{
			if (!isset($params[$field]) || $params[$field] == $this->record[$field])
			{
				continue;
			}

			$update_fields[] = $field;
			$update_values[] = $params[$field];
		}

		if (count($update_fields) > 0)
		{
			$update_values[] = $this->record[static::DB_KEY];
			if (!$db->Execute('update '.static::DB_TABLE.' set '.implode('=?, ', $update_fields).'=? where '.static::DB_KEY.'=?', $update_values))
			{
				$this->error = $db->errorMsg();
				return false;
			}
			return true;
		}

		// nothing changed?
		return true;
	}

	public function add(array $params): bool
	{
		global $db;
		$this->error = '';

		$add_cols = [];
		$add_vals = [];
		foreach (static::DB_FIELDS as $field)
		{
			if (!isset($params[$field]))
			{
				continue;
			}

			$add_cols[] = $field;
			$add_vals[] = $db->qstr($params[$field]);
		}

		if (count($add_cols) > 0)
		{
			if (!$db->Execute('insert into '.static::DB_TABLE.'('.implode(',', $add_cols).') values('.implode(', ', $add_vals).')'))
			{
				$this->error = $db->ErrorMsg();
				return false;
			}

			$this->record = $params;
			if ($db->insert_Id() > 0)
			{
				$this->record[static::DB_KEY] = $db->insert_Id();
			}

			return true;
		}

		// no data provided?
		$this->error = 'no data provided';
		return false;
	}

	public function delete(): bool
	{
		global $db;
		$this->error = '';

		if (!$db->Execute('delete from '.static::DB_TABLE.' WHERE '.static::DB_KEY.'=?', [$this->record[static::DB_KEY]]))
		{
			$this->error = $db->ErrorMsg();
			return false;
		}

		return true;
	}

	public function offsetExists($offset)
	{
		return in_array($offset, static::DB_FIELDS) || in_array($offset, $this->virtual_fields);
	}

	public function offsetGet($offset)
	{
		if (in_array($offset, $this->virtual_fields))
		{
			return $this->get($offset);
		}

		return $this->record[$offset] ?? '';
	}

	public function offsetSet($offset, $value)
	{
		throw new Exception('not implemented');
	}

	public function offsetUnset ($offset)
	{
		throw new Exception('not implemented');
	}

	public function get($offset)
	{
		return 'unimplemented';
	}
}
