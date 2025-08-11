<?php
date_default_timezone_set('America/Sao_Paulo');

require_once('library/activerecord/activeRecord.php');
$cfg = ActiveRecord\Config::instance();
$cfg->set_model_directory('../models/');

$cfg->set_connections(array('development' => 'mysql://u409234323_maismed:>7pKlB4BR@151.106.99.7/u409234323_maismed'));

?>
