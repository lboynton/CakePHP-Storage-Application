<?php
class NumberComponent extends Object 
{
	function convert($number, $old_unit, $new_unit)
	{
		switch($old_unit . ' to ' . $new_unit)
		{
			case 'b to b':
				return $number;
				break;
			
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
	
	/**
	 * Returns a formatted-for-humans file size.
	 *
	 * @param integer $length Size in bytes
	 * @return string Human readable size
	 * @static
	 */
	function toReadableSize($size) 
	{
		$size = intval($size);

		switch($size) 
		{
			case 0:
				$value = 0;
				$shortUnit = 'b';
				$longUnit = 'Bytes';
				break;
				
			case 1:
				$value = 1;
				$shortUnit = 'b';
				$longUnit = 'Byte';
				break;

			case $size < 1024:
				$value = $size;
				$shortUnit = 'b';
				$longUnit = 'Bytes';
				break;

			case $size < 1024 * 1024:
				$value = $this->precision($size / 1024, 0);
				$shortUnit = 'kb';
				$longUnit = 'Kilobytes';
				break;
				
			case $size < 1024 * 1024 * 1024:
				$value = $this->precision($size / 1024 / 1024, 2);
				$shortUnit = 'mb';
				$longUnit = 'Megabytes';
				break;
				
			case $size < 1024 * 1024 * 1024 * 1024:
				$value = $this->precision($size / 1024 / 1024 / 1024, 2);
				$shortUnit = 'gb';
				$longUnit = 'Gigabytes';
				break;
				
			case $size < 1024 * 1024 * 1024 * 1024 * 1024:
				$value = $this->precision($size / 1024 / 1024 / 1024 / 1024, 2);
				$shortUnit = 'tb';
				$longUnit = 'Terabytes';
				break;
		}
		
		return array('value' => $value, 'shortUnit' => $shortUnit, 'longUnit' => $longUnit);
	}
	
	/**
	 * Formats a number with a level of precision.
	 *
	 * @param  float	$number	A floating point number.
	 * @param  integer $precision The precision of the returned number.
	 * @return float Enter description here...
	 * @static
	 */
	function precision($number, $precision = 3) 
	{
		return sprintf("%01.{$precision}f", $number);
	}
}
?>
