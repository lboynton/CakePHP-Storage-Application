<?php
class NumberComponent extends Object 
{
	function convert($number, $old_unit, $new_unit)
	{
		switch($old_unit . ' to ' . $new_unit)
		{
			case 'b to kb':
			case 'kb to mb':
			case 'mb to gb':
				return $number / 1024;
				
			case 'b to mb':
			case 'kb to gb':
				return ($number / 1024) / 1024;
				
			case 'b to gb':
				return (($number / 1024) / 1024) / 1024;
				
			case 'kb to b':
			case 'mb to kb':
			case 'gb to mb':
				return $number * 1024;
			
			case 'mb to b':
			case 'gb to kb':
				return ($number * 1024) * 1024;
			
			case 'gb to b':
				return (($number * 1024) * 1024) * 1024;
		}
	}
}
?>
