<?php

Class Filter {

	public static function getFilters($type) {
		if ($type == 'all')
			$query = "SELECT * FROM `Filter`";
		else
			$query = "SELECT * FROM `Filter` WHERE type = ':type'";

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
