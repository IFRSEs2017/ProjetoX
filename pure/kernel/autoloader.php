<?php

/**
 * Script de carregamento automático de classes
 * @param string $classname
 */
 spl_autoload_register(
	function ($classname)
	{
		$classname = ltrim($classname, '\\');
		$filename  = '';
		$namespace = '';
		if ($lastnspos = strripos($classname, '\\'))
		{
			$namespace = substr($classname, 0, $lastnspos);
			$namespace = strtolower($namespace);
			$classname = substr($classname, $lastnspos + 1);
			$filename  = str_replace('\\', '/', $namespace) . '/';
		}
		$filename .= str_replace('_', '/', $classname) . '.php';
		$to_require = BASE_PATH.$filename;
		if (!file_exists($to_require))
		{
			return false;
		}
		require_once($to_require);
		return true;
	}
);
?>