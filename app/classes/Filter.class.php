<?php

Class Filter {

	public static function getFilters($type) {
		if ($type == 'all')
			$query = "SELECT * FROM `filter`";
		else
			$query = "SELECT * FROM `filter` WHERE type = ':type'";

		$params = array(
			':type' 	=> array( $type, PDO::PARAM_STR )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		$queryResult = $query->fetchAll();
		return ($queryResult);
	}

}
