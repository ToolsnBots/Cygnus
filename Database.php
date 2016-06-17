<?php
class Database extends mysqli {
	public function replicaConnect($database) {
		$mycnf = parse_ini_file( "./replica.my.cnf" );
		$cluster = ( preg_match( '/[-_]p$/', $database ) ) ? substr( $database, 0, -2 ) : $database;
		parent::connect($cluster . '.labsdb', $mycnf['user'], $mycnf['password']);
		unset($mycnf);
		if( $this->connect_error ) {
			die( '<p><strong>Database server login failed.</strong> '
			. ' This is probably a temporary problem with the server and will be fixed soon. '
			. ' The server returned error code ' . $this->connect_errno . '.</p>' );
		}
		$res = $this->select_db(str_replace('-', '_', $database));
		if( $res === false ) {
			die( '<p><strong>Database selection failed.</strong> '
			. ' This is probably a temporary problem with the server and will be fixed soon.</p>' );
		}
	}
	public static function getName($lang, $project, $separator = '-') {
		if ($project == 'wikipedia') {
			$project = 'wiki';
		} elseif ($project == 'wikimedia') {
			$project = 'wiki';
		} elseif($project == 'wikidata') {
			$project = 'wiki';
			$lang = 'wikidata';
		}
		return $lang . $project . $separator . 'p';
	}
}