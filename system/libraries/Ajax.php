<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Code Igniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, pMachine, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Code Igniter AJAX Class
 *
 * This class enables you to integrate AJAX into your web apps.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Siric
 * @link		http://www.no-link-yet.com
 */

class JavaScript {

	function button_to_function($name,$function=null)
	{
		return '<input type="button" value="'.$name.'" onclick="'.$function.'" />';
	}

	function escape($javascript)
	{
		$javascript=str_replace(array("\r\n","\n","\r"),array("\n"),$javascript);
		$javascript=addslashes($javascript);
		return $javascript;
	}

	function tag($content)
	{
		return '<script type="text/javascript">'.$content.'</script>';
	}

	function link_to_function($name,$function,$html_options=null)
	{
		return '<a href="'.((isset($html_options['href']))?$html_options['href']:'#').'" onclick="'.((isset($html_options['onclick']))?$html_options['onclick'].';':'').$function.'; return false;" />'.$name.'</a>';
	}

	function _array_or_string_for_javascript($option)
	{
		$return_val='';

		if(is_array($option))
		{
			foreach ($option as $value)
      {
				if(!empty($return_val))$ret_val.=', ';
				$return_val.=$value;
			}

			return '['.$return_val.']';
		}

			return "'$option'";
	}

	function _options_for_javascript($options)
	{
		$return_val='';

		if (is_array($options))
    {
  		foreach ($options as $var=>$val)
  		{
  			if (!empty($return_val)) $return_val.=', ';
  			$return_val.="$var:$val";
  		}
		}

		return '{'.$return_val.'}';
	}

}

class Prototype extends JavaScript {

	var $CALLBACKS = array('uninitialized',
							'loading',
							'loaded',
							'interactive',
							'complete',
							'failure',
							'success');

	var $AJAX_OPTIONS = array('before',
							   'after',
							   'condition',
							   'url',
							   'asynchronous',
							   'method',
							   'insertion',
							   'position',
							   'form',
							   'with',
							   'update',
							   'script',
							   'uninitialized',
							   'loading',
							   'loaded',
							   'interactive',
							   'complete',
							   'failure',
							   'success');

	function evaluate_remote_response()
  {
		return 'eval(request.responseText)';
	}

	function form_remote_tag($options)
	{
		$options['form'] = true;
		return '<form action="'.$options['url'].'" onsubmit="'.$this->remote_function($options).'; return false;" method="'.(isset($options['method'])?$options['method']:'post').'"  >';
	}

	function link_to_remote($name,$options=null,$html_options=null)
	{
		return $this->link_to_function($name,$this->remote_function($options),$html_options);
	}

	function observe_field($field_id,$options=null)
	{
		if (isset($options['frequency']) && $options['frequency']> 0 ) {
			return $this->_build_observer('Form.Element.Observer',$field_id,$options);
		}
    else
    {
			return $this->_build_observer('Form.Element.EventObserver',$field_id,$options);
		}
	}

	function observe_form($form_id,$options = null)
	{
		if (isset($options['frequency']))
    {
			return $this->_build_observer('Form.Observer',$form_id,$options);
		}
    else
    {
			return $this->_build_observer('Form.EventObserver',$form_id,$options);
		}
	}

	function periodically_call_remote($options=null)
  {
		$frequency=(isset($options['frequency']))?$options['frequency']:10;
		$code = 'new PeriodicalExecuter(function() {'.$this->remote_function($options).'},'.$frequency.')';
		return $code;
	}

	function remote_function($options)
	{
		$javascript_options = $this->_options_for_ajax($options);
		$update ='';

		if(isset($options['update']) && is_array($options['update']))
		{
			$update=(isset($options['update']['success']))?'success: '.$options['update']['success']:'';
			$update.=(empty($update))?'':",";
			$update.=(isset($options['update']['failure']))?'failure: '.$options['update']['failure']:'';
		}
    else
    {
			$update.=(isset($options['update']))?$options['update']:'';
		}

		$ajax_function=(empty($update))?'new Ajax.Request(':'new Ajax.Updater(\''.$update.'\',';

		$ajax_function.="'".$options['url']."'";
		$ajax_function.=",".$javascript_options.')';

		$ajax_function=(isset($options['before']))?  $options['before'].';'.$ajax_function : $ajax_function;
		$ajax_function=(isset($options['after']))?  $ajax_function.';'.$options['after'] : $ajax_function;
		$ajax_function=(isset($options['condition']))? 'if ('.$options['condition'].') {'.$ajax_function.'}' : $ajax_function;
		$ajax_function=(isset($options['confirm'])) ? 'if ( confirm(\''.$options['confirm'].'\' ) ) { '.$ajax_function.' } ':$ajax_function;

		return $ajax_function;
	}

	function submit_to_remote($name,$value,$options)
  {
		if(isset($options['with']))
    {
			$options['with'] ="Form.serialize(this.form)";
		}

		return '<input type="button" onclick="'.$this->remote_function($options).'" name="'.$name.'" value ="'.$value.'" />';
	}

	function update_element_function($element_id,$options=null,$block)
	{
		$content=(isset($options['content']))?$options['content']:'';
		$content=$this->escape($content);
	}

	function update_page($block)
	{
	}

	function update_page_tag(& $block)
	{
		return $this->tag($block);
	}

	function _build_callbacks($options)
	{
		$callbacks=array();

		foreach ($options as $callback=>$code)
    {
			if (in_array($callback,$this->CALLBACKS))
      {
  			$name = 'on'.ucfirst($callback);
  			$callbacks[$name]='function(request){'.$code.'}';
			}
		}

		return $callbacks;
	}

	function _build_observer($klass,$name,$options=null)
	{
		if (isset($options['with']) && !strstr($options['with'],'='))
    {
			$options['with'] = '\''.$options['with'].'=\' + value';
		}
    elseif (isset($options['with']) && isset($options['update']))
    {
			$options['with'] = 'value';
		}

		$callback = $options['function'] ? $options['function'] : $this->remote_function($options);

		$javascript = "new $klass('$name', ";
		$javascript.= (isset($options['frequency']))?$options['frequency'].', ':'';
		$javascript.= 'function (element,value) { ';
		$javascript.= $callback;
		$javascript.=  (isset($options['on']))?', '.$options['on']:'';
		$javascript.= '})';

		return $javascript;
	}

	function _method_option_to_s($method)
	{
		return (strstr($method,"'"))?$method:"'$method'";
	}

	function _options_for_ajax($options)
	{
		$js_options=(is_array($options))?$this->_build_callbacks($options):array();

		if (isset($options['type']) && $option['type']=='synchronous')	$js_options['asynchronous'] ='false';
		if (isset($options['method'])) $js_options['method']    = $this->_method_option_to_s($options['method']);
		if (isset($options['position'])) $js_options['insertion']    = 'Insertion.'.ucfirst($options['position']);

		$js_options['evalScripts'] = isset($options['script'])?$options['script']:'true';

		if (isset($options['form']))
    {
			$js_options['parameters']='Form.serialize(this)';
		}
    elseif (isset($options['parameters']))
    {
			$js_options['parameters']='Form.serialize(\''.$options['submit'].'\')';
		}
    elseif (isset($options['with']))
    {
			$js_options['parameters']= $options['with'];
		}

		return $this->_options_for_javascript($js_options);
	}

	function dump($javascript)
	{
		echo $javascript;
	}

	function ID($id,$extend=null)
	{
		return "$('$id')".(!empty($extend))?'.'.$extend.'()':'';
	}

	function alert($message)
	{
		return $this->call('alert',$message);
	}

	function assign($variable,$value)
	{
		return "$variable = $value;";
	}

	function call($function , $args = null)
	{
		$arg_str='';
		if (is_array($args))
    {
			foreach ($args as $arg)
      {
				if (!empty($arg_str))$arg_str.=', ';

				if (is_string($arg))
        {
					$arg_str.="'$arg'";
				}
        else
        {
					$arg_str.=$arg;
				}
			}
		}
    else
    {
			if (is_string($arg))
      {
				$arg_str.="'$arg'";
			}
      else
      {
				$arg_str.=$arg;
			}
		}

		return "$function($arg_str)";
	}

	function delay($seconds=1,$script='')
	{
		return "setTimeout( function() { $script } , ".($seconds*1000)." )";
	}

	function hide($id)
	{
		return $this->call('Element.hide',$id);
	}

	function insert_html($position,$id,$options_for_render=null)
	{
		$args=array_merge(array($id),(is_array($options_for_render)?$options_for_render:array($options_for_render)));
		return $this->call('new Insertion.'.ucfirst($position),$args);
	}

	function redirect_to($location)
	{
		return $this->assign('window.location.href',$location);
	}

	function remove($id)
	{
		if (is_array($id))
    {
			$arr_str='';
			foreach ($id as $obj)
      {
				if(!empty($arg_str))$arg_str.=', ';
				$arg_str.="'$arg'";
			}

			return "$A[$arg_str].each(Element.remove)";
		}
    else
    {
			return "Element.remove('$id')";
		}
	}

	function replace($id,$options_for_render)
	{
		$args=array_merge(array($id),(is_array($options_for_render)?$options_for_render:array($options_for_render)));
		return $this->call('Element.replace',$args);
	}

	function replace_html($id,$options_for_render)
	{
		$args=array_merge(array($id),(is_array($options_for_render)?$options_for_render:array($options_for_render)));
		return $this->call('Element.update',$args);
	}

	function select($pattern,$extend=null)
	{
		return "$$('$pattern')".(!empty($extend))?'.'.$extend:'';
	}

	function show($id)
	{
		return $this->call('Element.show',$id);
	}

	function toggle($id)
	{
		return $this->call('Element.toggle',$id);
	}

}

class Scriptaculous extends Prototype {

	var $TOGGLE_EFFECTS = array('toggle_appear', 'toggle_slide','toggle_blind');

	function Scriptaculous()
  {
	}

	function dragable_element($element_id,$options=null)
	{
		return $this->tag($this->_dragable_element_js($element_id,$options));
	}

	function drop_receiving_element($element_id,$options=null)
	{
		return $this->tag($this->_drop_receiving_element($element_id,$options));
	}

	function visual_effect($name,$element_id=false,$js_options=null)
  {
		$element=($element_id)?"'$element_id'":'element';

		$js_queue ='';

		if(isset($js_options) && is_array($js_options['queue']))
    {
		}
    elseif (isset($js_options))
    {
			$js_queue="'$js_options'";
		}

		if(in_array($name,$this->TOGGLE_EFFECTS))
    {
			return "Effect.toggle($element,'".str_replace('toggle_','',$name)."',".$this->_options_for_javascript($js_options).')';
		}
    else
    {
			return  "new Effect.".ucwords($name)."($element,".$this->_options_for_javascript($js_options).')';
		}
	}

	function sortabe_element($element_id,$options=null)
	{
		return $this->tag($this->_sortabe_element($element_id,$options));
	}

	function _sortabe_element($element_id,$options)
	{
		//if(isset($options['with']))
		{
			$options['with'] ="Sortable.serialize('$element_id')";
		}

		//if (isset($option['onUpdate']))
		{
			$options['onUpdate'] ="function(){". $this->remote_function($options) ."}";
		}

		foreach ($options as $var=>$val)if(in_array($var,$this->AJAX_OPTIONS))unset($options[$var]);

		$arr = array('tag','overlap','contraint','handle');

		foreach ($arr as $var)
    {
			if (isset($options[$var]))
      {
				$options[$var]	= "'".$options[$var]."'";
			}
		}

		if (isset($options['containment']))
    {
			$options['containment'] =$this->_array_or_string_for_javascript($options['containment']);
		}

		if (isset($options['only']))
    {
			$options['only'] =$this->_array_or_string_for_javascript($options['only']);
		}

		return "Sortable.create('$element_id',".$this->_options_for_javascript($options).')';

	}

	function _dragable_element_js($element_id,$options)
	{
		return 'new Draggable(\''.$element_id.'\','.$this->_options_for_javascript($options).')';
	}

	function _drop_receiving_element($element_id,$options)
	{
		//if(isset($options['with']))
		{
			$options['with'] = '\'id=\' + encodeURIComponent(element.id)';
		}

		//if (isset($option['onDrop']))
		{
			$options['onDrop'] ="function(element){". $this->remote_function($options) ."}";
		}

		if (is_array($options))
    {
			foreach ($options as $var=>$val)if(in_array($var,$this->AJAX_OPTIONS))unset($options[$var]);
		}

		if (isset($options['accept']))
    {
			$options['accept'] =$this->_array_or_string_for_javascript($options['accept']);
		}

		if (isset($options['hoverclass']))
    {
			$options['hoverclass'] ="'".$options['hoverclass']."'";
		}

		return 'Droppables.add(\''.$element_id.'\','. $this->_options_for_javascript($options).')';
	}

  function in_place_editor($field_id,$options,$tag=true)
  {
    $function  =  "new Ajax.InPlaceEditor(";
    $function .= "'$field_id', ";
    $function .= "'".$options['url']."'";

    $js_options=array();
    if (isset($options['cancel_text']))$js_options['cancelText']=$options['cancel_text'];
    if (isset($options['save_text']))$js_options['okText']=$options['save_text'];
    if (isset($options['loading_text']))$js_options['loadingText']=$options['loading_text'];
    if (isset($options['rows']))$js_options['rows']=$options['rows'];
    if (isset($options['cols']))$js_options['cols']=$options['cols'];
    if (isset($options['size']))$js_options['size']=$options['size'];
    if (isset($options['external_control']))$js_options['externalControl']="'".$options['external_control']."'";
    if (isset($options['load_text_url']))$js_options['loadTextURL']="'".$options['load_text_url']."'";
    if (isset($options['options']))$js_options['ajaxOptions']=$options['options'];
    if (isset($options['script']))$js_options['evalScripts']=$options['script'];
    if (isset($options['with']))$js_options['callback']="function(form) { return ".$options['with']." }";

    $function.= ', '.$this->_options_for_javascript($js_options).' )';

    if($tag)return $this->tag($function);
    else return $function;
  }

	function in_place_editor_field($object,$tag_options=null,$in_place_editor_options=null)
	{
		$ret_val='';
		$ret_val.='<span id="'.$object.'" class="in_place_editor_field">'.(isset($tag_options['value'])?$tag_options['value']:'').'</span>';
		$ret_val.=$this->in_place_editor($object,$in_place_editor_options);
		return $ret_val;
	}

	function auto_complete_field($field_id,$options)
  {
		$function = "var $field_id"."_auto_completer = new Ajax.Autocompleter(";
		$function.= "'$field_id', ";
		$function.= "'".(isset($options['update'])?$options['update']:$field_id.'_auto_complete')."', ";
		$function.= "'".$options['url']."'";

		$js_options=array();
		if (isset($options['tokens']))$js_options['tokens']=$this->javascript->_array_or_string_for_javascript($options['tokens']);
		if (isset($options['with']))$js_options['callback']="function(element, value) { return ".$options['with']." }";
		if (isset($options['indicator']))$js_options['indicator']="'".$options['indicator']."'";
		if (isset($options['select']))$js_options['select']="'".$options['select']."'";

		foreach (array('on_show'=>'onShow','on_hide'=>'onHide','min_chars'=>'min_chars') as $var=>$val)
    {
			if (isset($options[$var])) $js_options['$val']=$options['var'];
		}

		$function.= ', '.$this->_options_for_javascript($js_options).' )';
		return $this->tag($function);
	}

	function auto_complete_results($entries,$field,$phrase=null)
  {
		if(!is_array($entries))return;
		$ret_val='<ul>';
	//	Complete this function
	}

	function text_field_with_auto_complete($object,$tag_options=null,$completion_options=null)
	{
		$ret_val=(isset($completion_options['skip_style']))?'':$this->_auto_complete_stylesheet();
		$ret_val.='<input autocomplete="off" id="'.$object.'" name="'.$object.'" size="'.(isset($tag_options['size'])?$tag_options['size']:30).'" type="text" value="'.(isset($tag_options['size'])?$tag_options['value']:'').'" '.(isset($tag_options['class'])?'class = "'.$tag_options['class'].'" ':'').'/>';

		$ret_val.='<div id="'.$object.'_auto_complete" class="auto_complete"></div>';
		$ret_val.=$this->auto_complete_field($object,$completion_options);
		return $ret_val;
	}

	function _auto_complete_stylesheet()
	{
		return '<style> div.auto_complete {
	              width: 350px;
	              background: #fff;
 	            }
	            div.auto_complete ul {
 	              border:1px solid #888;
 	              margin:0;
 	              padding:0;
 	              width:100%;
 	              list-style-type:none;
 	            }
 	            div.auto_complete ul li {
 	              margin:0;
 	              padding:3px;
 	            }
 	            div.auto_complete ul li.selected {
 	              background-color: #ffb;
 	            }
 	            div.auto_complete ul strong.highlight {
 	              color: #800;
 	              margin:0;
 	              padding:0;
 	            }
 	            </style>';
	}

}

class Ajax extends Scriptaculous { }

?>