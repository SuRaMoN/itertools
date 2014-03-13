<?php

namespace itertools;

use itertools\SubstringLocation;


class TemplateToSubstringMapConverter
{
	public function __construct()
	{
	}

	public function convert($template, array $nameMap = array())
	{
		preg_match_all('/<[a-zA-Z0-9_ -]+>|[a-zA-Z0-9_-]+/', $template, $substrings, PREG_OFFSET_CAPTURE);
		$map = array();
		foreach($substrings[0] as $i => $substring) {
			$name = trim($substring[0], '< >');
			$name = array_key_exists($name, $nameMap) ? $nameMap[$name] : $name;
			if(strpos($substring[0], '<') === false) {
				$templatePartAfterField = substr($template, $substring[1] + strlen($substring[0]));
				$map[$name] = new SubstringLocation($substring[1], strlen($substring[0]) + strspn($templatePartAfterField, ' '));
			} else {
				$map[$name] = new SubstringLocation($substring[1], strlen($substring[0]));
			}
		}
		return $map;
	}
}
 
