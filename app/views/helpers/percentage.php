<?php
class PercentageHelper extends AppHelper 
{
	var $helpers = array('Number');
	
	function show($n)
	{	
		if($n <= 33.33)
		{
			$class = "low";
		}
		elseif($n <= 66.66)
		{
			$class = "medium";
		}
		else
		{
			$class = "high";
		}
		
		return $this->output('<span class="'.$class.'">'.$this->Number->toPercentage($n).'</span>');
	}
	
	function chart($n, $showNumber = false)
	{
		$output = '<div class="chartBG">';
		
		if($showNumber) $output .= '<p>' . $this->Number->toPercentage($n) . '</p>';
		
		$output .= '<div class="chartFG" style="width:' . $n . '%">';
		
		if($showNumber) $output .= '<p>' . $this->Number->toPercentage($n) . '</p>';
		
		$output .= '</div></div>';
		
		return $this->output($output);
	}
}
?>