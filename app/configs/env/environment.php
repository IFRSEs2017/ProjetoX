<?php
/**
* Realiza a definição do ambiente de desenvolvimento
* de acordo com o valor definido em index.php
*/
var_dump(PURE_ENV);
switch(PURE_ENV){
	case 'development':
		require(BASE_PATH . 'app/configs/env/development/database.php');
		require(BASE_PATH . 'app/configs/env/development/variables.php');
		break;
	case 'production':
		require(BASE_PATH . 'app/configs/env/production/database.php');
		require(BASE_PATH . 'app/configs/env/production/variables.php');
		break;
	case 'test':
		// CREATE
		break;
	default:
		require(BASE_PATH . 'app/configs/env/development/database.php');
		require(BASE_PATH . 'app/configs/env/development/variables.php');
		break;
}
?>