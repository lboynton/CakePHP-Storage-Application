<?php
class MenuHelper extends AppHelper 
{
    function menuItem($title, $url) 
	{
		return $this->output
		(
			"<a href=\"$url\" class=\"edit\">
				<span class=\"menuItemLeft\"></span>
				$title
				<span class=\"menuItemRight\"></span>
			</a>"
		);
    }
	
	function mainMenu($links = array(), $htmlAttributes = array())
	{
		$out[] = "<ul>";
		
		foreach ($links as $title => $link)
		{
			$parsedLink = Router::parse($link);
			
			// append prefix to action if prefix is set
			if(isset($parsedLink['prefix'])) 
			{
				$parsedLink['action'] = $parsedLink['prefix'] . '_' . $parsedLink['action'];
			}
			
			if($parsedLink['controller'] == $this->params['controller'] && $parsedLink['action'] == $this->params['action'])
			{
				$out[] = sprintf
				(
					"<li%s><a href=\"%s\" class=\"active\"><span class=\"topLeft\"></span>%s<span class=\"topRight\"></span></a></li>", 
					$this->_parseAttributes($htmlAttributes), $link, $title
				);
			}
			else
			{
				$out[] = sprintf
				(
					"<li%s><a href=\"%s\"><span class=\"topLeft\"></span>%s<span class=\"topRight\"></span></a></li>", 
					$this->_parseAttributes($htmlAttributes), $link, $title
				);
			}
		}
		
		$out[] = "</ul>";
		
		$tmp = join("\n", $out);
        return $this->output($tmp);
	}
	
    function menu2($links = array(),$htmlAttributes = array(),$type = 'ul')
    {      
        $this->tags['ul'] = '<ul%s>%s</ul>';
        $this->tags['ol'] = '<ol%s>%s</ol>';
        $this->tags['li'] = '<li%s>%s</li>';
        $out = array();        
        foreach ($links as $title => $link)
        {
            if($this->url($link) == substr($this->here,0,-1))
            {
                $out[] = sprintf($this->tags['li'],' class="active"',$this->Html->link($title, $link));
            }
            else
            {
                $out[] = sprintf($this->tags['li'],'',$this->Html->link($title, $link));
            }
        }
        $tmp = join("\n", $out);
        return $this->output(sprintf($this->tags[$type],$this->_parseAttributes($htmlAttributes), $tmp));
    } 
	
	function normalMenu($links = array(),$htmlAttributes = array())
	{
		$out[] = sprintf("<ul%s>", $this->_parseAttributes($htmlAttributes));
		
		foreach ($links as $title => $link)
		{
			$out[] = sprintf('<li><a href="%s">%s</a></li>',$link, $title);
		}
		
		$out[] = "</ul>";
		
		$tmp = join("\n", $out);
        return $this->output($tmp);
	}
}
?>
