<?php
defined( 'ABSPATH' ) || exit;
/**
 * Table of contents
 *
 * @package Table of contents Maker
 */


add_filter('the_content','toc_maker_replace_content', 1000);

function toc_maker_replace_content($the_content) {

  $the_content = toc_maker_make_toc($the_content);
  return $the_content;

}


function toc_maker_make_toc($the_content) {

  $load_setting = get_option('toc_maker_settings');

  
  if( !in_array(get_post_type(), $load_setting['auto_insert_type']) ){
    return $the_content;
  }


  $toc['toc_title'] = apply_filters( 'toc_maker_title', isset($load_setting['toc_title']) ? esc_html($load_setting['toc_title']) : esc_html_x( 'Table of contents', 'Widget Title' , 'toc-maker' ) );

  
  $toc['min_head'] = $load_setting['min_head'];

  
  
  $toc['toc_position'] = $load_setting['toc_position'];

  
  $toc['hierarchy'] = $load_setting['hierarchy'];

  
  $toc['numerical'] = $load_setting['numerical'];

  
  $toc['hide_at_first'] = $load_setting['hide_at_first'];

  
  


  
  

  $toc_data['page_permalink'] = array();

  $toc_data['heading_num'] = '';
  $toc_data['heading_title'] = '';


  
  

    

    

  

  
  
    $heading = array();
    $heading_count = preg_match_all( '/<h([1-6]).*?>(.*?)<\/h[1-6].*?>/iu', $the_content, $heading );
  

  
  
  if( $heading_count < $toc['min_head'] ){
    
    
    

    return $the_content;
  }

    //$numif = $toc['numerical'] ? '1' : "0" ;
    //$hieif = $toc['hierarchy'] ? '1' : "0" ;

  //$toc_html['caret'] = '<svg width="10" height="10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="svg_icon" d="M18.8,12c0,0.4-0.2,0.8-0.4,1.1L7.8,23.6C7.5,23.8,7.1,24,6.8,24c-0.8,0-1.5-0.7-1.5-1.5v-21C5.3,0.7,5.9,0,6.8,0 c0.4,0,0.8,0.2,1.1,0.4l10.5,10.5C18.6,11.2,18.8,11.6,18.8,12z"></path></svg>';
  $toc_html['caret'] = '<svg width="10" height="10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="svg_icon" d="M24,6.8c0,0.4-0.2,0.8-0.4,1.1L13.1,18.3c-0.3,0.3-0.7,0.4-1.1,0.4s-0.8-0.2-1.1-0.4L0.4,7.8C0.2,7.5,0,7.1,0,6.8 c0-0.8,0.7-1.5,1.5-1.5h21C23.3,5.3,24,5.9,24,6.8z"></path></svg>';

  $toc_html['header'] = '
  <div class="toc_maker_nav_wrap">
  <nav id="toc_maker_nav" class="toc_maker_nav">
  <input id="toc_maker_toggle" class="toc_maker_toggle" type="checkbox"'. (!$toc['hide_at_first'] ? '': ' checked' ) .' />
  <label for="toc_maker_toggle" class="toc_maker_ctrl">
  <span class="toc_maker_caret">'.$toc_html['caret'].'</span>
  <div class="toc_maker_title">'.esc_html($toc['toc_title']).'</div>
  </label>
  '."\n";
  $toc_html['footer'] = '</nav></div>';
  $toc_html['content'] = '';




  if($toc['hierarchy']){
    
    $toc_back_data = toc_maker_hierarchy($the_content,$heading,$toc['numerical'],$toc_data );
  }else{
    
    $toc_back_data = toc_maker_non_hierarchy($the_content,$heading, $toc['numerical'],$toc_data );
  }

  $toc_html['content'] = $toc_back_data['html_content'];
  $the_content = $toc_back_data['the_content'];

  
  

    $toc_data['heading_num'] = $heading[1][0];
    $toc_data['heading_title'] = $heading[2][0];

  

  
  $pattern = '{<h'.$toc_data['heading_num'].'(.*?)>(.*?)'.toc_maker_special_character_replace($toc_data['heading_title']).'(.*?)<\/h'.$toc_data['heading_num'].'>}ismu';
  if($toc['toc_position'] === 'before_1st_heading' ){
    
    $replacement = $toc_html['header'].$toc_html['content'].$toc_html['footer']."\n".'<h'.$toc_data['heading_num'].'$1>${2}'.$toc_data['heading_title'].'$3</h'.$toc_data['heading_num'].'>';
    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
  }else if ($toc['toc_position'] === 'after_1st_heading' ){
    
    $replacement = '<h'.$toc_data['heading_num'].'$1>${2}'.$toc_data['heading_title'].'$3</h'.$toc_data['heading_num'].'>'.$toc_html['header'].$toc_html['content'].$toc_html['footer'];
    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
  }else if ($toc['toc_position'] === 'before_content' ){
    
    $the_content  = $toc_html['header'].$toc_html['content'].$toc_html['footer'].$the_content;
  }

  set_query_var('toc_maker', true);

  if( is_active_widget( false, false, 'toc_maker_widget', true ) && $toc_html['content'] !== ''){
    $pattern = '/<input id="toc_maker_toggle".*?<\/label>\]<\/div>/iu';
    $toc_html['header']  = '<div class="toc_maker_widget">';
    $toc_html['footer']  = '</div>';

    $toc_set_query_var = array();
    $toc_set_query_var['the_content'] = $the_content;
    $toc_set_query_var['heading'] = $heading;
    $toc_set_query_var['toc_data'] = $toc_data;

    set_query_var( 'toc_set_query_var', $toc_set_query_var );

  }


  return $the_content;

}

function toc_maker_special_character_replace($replace) {
  
  $brackets_search = array(
    '\\',
    '?',
    '*',
    '+',
    '.',
    '(',
    ')',
    '{',
    '}',
    '[',
    ']',
    '^',
    '$',
    '-',
    '|',
    '=',
    '!',
    '<',
    '>',
    ':',
  );
  $brackets_replace = array(
    '\\\\',
    '\?',
    '\*',
    '\+',
    '\.',
    '\(',
    '\)',
    '\{',
    '\}',
    '\[',
    '\]',
    '\^',
    '\$',
    '\-',
    '\|',
    '\=',
    '\!',
    '\<',
    '\>',
    '\:',
  );
  return str_replace($brackets_search,$brackets_replace,$replace);
}

function toc_maker_hierarchy($the_content,$heading, $is_numerical,$toc_data) {

  
  

  $toc_data['top_level'] = 6;
  foreach($heading[1] as $temp){
    
    if($temp < $toc_data['top_level']) $toc_data['top_level'] = (int)$temp;
  }

  $heading_no[1] = $heading_no[2] = $heading_no[3] = $heading_no[4] = $heading_no[5] = $heading_no[6] = $num_heading[1] = $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;

  $before_level = $toc_data['top_level'];
  $after_level = (int)$heading[1][1];

  $numerical = '';

  $toc_back_data['html_content'] = '<ul class="toc_maker_ul">';

  foreach( $heading[1] as $no => $now ){

    $now = (int)$now;

    $toc_data['page_permalink'][$no] = '';

    $num_heading[$now]++;
    $heading_no[$now]++;
    $link_number = $heading_no[$now];
    $currnt_level = $now;


    $pattern = '{<h'.$now.'(.*?)>'.preg_quote($heading[2][$no]).'<\/h'.$now.'>}isum';
    $replacement = '<h'.$now.'$1><span id="heading'.$now.'_'.$link_number.'">'.$heading[2][$no].'</span></h'.$now.'>';

    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
    
    if($is_numerical){
      switch ($now){
        case 1:
        $numerical = $num_heading[1].' ';
        $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 2:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).$num_heading[2].' ';
        $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 3:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).$num_heading[3].' ';
        $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 4:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).$num_heading[4].' ';
        $num_heading[5] = $num_heading[6] = 0;
        break;
        case 5:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).($toc_data['top_level'] < 5 ? $num_heading[4].'.' : '' ).$num_heading[5].' ';
        $num_heading[6] = 0;
        break;
        case 6:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).($toc_data['top_level'] < 5 ? $num_heading[4].'.' : '' ).($toc_data['top_level'] < 6 ? $num_heading[5].'.' : '' ).$num_heading[6].' ';
        break;
        default:
        $numerical = '';
      }
      
    }

    if($before_level === $currnt_level){
      $toc_back_data['html_content'] .= '<li>'.esc_html($numerical).'<a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }else if ($currnt_level > $before_level ){

      while($currnt_level !== $before_level){
        $toc_back_data['html_content'] .= '<ul><li>';
        $currnt_level-- ;
      }
      $toc_back_data['html_content'] .= esc_html($numerical).'<a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }else{
      
      $toc_back_data['html_content'] .= '<li>'.esc_html($numerical).'<a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }
    $before_level = $now;

    if(isset($heading[1][$no + 1])) $after_level = (int)$heading[1][$no + 1];

    if($before_level === $after_level){
      $toc_back_data['html_content'] .= '</li>'."\n";

    }else if($before_level > $after_level){

      $diff_level = $before_level - $after_level;
      while($diff_level > 0){
        $toc_back_data['html_content'] .= '</li></ul></li>'."\n";
        $diff_level-- ;
      }

      $toc_back_data['html_content'] .= ''."\n";
      

    }else{

      $toc_back_data['html_content'] .= "\n";
      
    }

  }

  if ($before_level > $toc_data['top_level']){
    $toc_back_data['html_content'] .= '</li>'."\n";
    $diff_level = $before_level;
    while($diff_level > 2){
      $toc_back_data['html_content'] .= '</ul></li>'."\n";
      $diff_level-- ;
    }
  }

  $toc_back_data['html_content'] .= '</ul>'."\n";

  $toc_back_data['the_content'] = $the_content;

  return $toc_back_data;

}

function toc_maker_non_hierarchy($the_content,$heading, $is_numerical,$toc_data) {


  $ulol = $is_numerical ? 'ol' : 'ul';
  $toc_back_data['html_content'] = '<'.esc_attr($ulol).' class="toc_maker_ul'.($ulol === 'ol' ? ' toc_maker_ol' : '').'>';

  $heading_no[1] = $heading_no[2] = $heading_no[3] = $heading_no[4] = $heading_no[5] = $heading_no[6] = $num_heading[1] = $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;

  foreach($heading[1] as $no => $now ){

    $now = (int)$now;
    $toc_data['page_permalink'][$no] = '';

    $heading_no[$now]++;
    $link_number = $heading_no[$now];


    $pattern = '{<h'.$now.'(.*?)>'.toc_maker_special_character_replace($heading[2][$no]).'<\/h'.$now.'>}isum';

    $replacement = '<h'.$now.'$1><span id="heading'.$now.'_'.$link_number.'">'.$heading[2][$no].'</span></h'.$now.'>';

    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
    $toc_back_data['html_content'] .= '<li><a href="'.esc_url($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number).'">'.wp_strip_all_tags($heading[2][$no]).'</a></li>'."\n";

  }
  $toc_back_data['html_content'] .= '</'.esc_attr($ulol).'>'."\n";

  $toc_back_data['the_content'] = $the_content;

  return $toc_back_data;

}


function toc_maker_widget_hierarchy($the_content,$heading, $is_numerical,$toc_data) {

  
  

  $toc_data['top_level'] = 6;
  foreach($heading[1] as $temp){
    
    if($temp < $toc_data['top_level']) $toc_data['top_level'] = (int)$temp;
  }

  $heading_no[1] = $heading_no[2] = $heading_no[3] = $heading_no[4] = $heading_no[5] = $heading_no[6] = $num_heading[1] = $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;

  $before_level = $toc_data['top_level'];
  $after_level = (int)$heading[1][1];

  $numerical = '';

  echo '<ul class="toc_maker_ul">';

  foreach( $heading[1] as $no => $now ){

    $now = (int)$now;

    $toc_data['page_permalink'][$no] = '';

    $num_heading[$now]++;
    $heading_no[$now]++;
    $link_number = $heading_no[$now];
    $currnt_level = $now;


    $pattern = '{<h'.$now.'(.*?)>'.preg_quote($heading[2][$no]).'<\/h'.$now.'>}isum';
    $replacement = '<h'.$now.'$1><span id="heading'.$now.'_'.$link_number.'">'.$heading[2][$no].'</span></h'.$now.'>';

    $the_content  = preg_replace($pattern, $replacement, $the_content,1);
    
    if($is_numerical){
      switch ($now){
        case 1:
        $numerical = $num_heading[1].' ';
        $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 2:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).$num_heading[2].' ';
        $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 3:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).$num_heading[3].' ';
        $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;
        break;
        case 4:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).$num_heading[4].' ';
        $num_heading[5] = $num_heading[6] = 0;
        break;
        case 5:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).($toc_data['top_level'] < 5 ? $num_heading[4].'.' : '' ).$num_heading[5].' ';
        $num_heading[6] = 0;
        break;
        case 6:
        $numerical = ($toc_data['top_level'] < 2 ? $num_heading[1].'.' : '' ).($toc_data['top_level'] < 3 ? $num_heading[2].'.' : '' ).($toc_data['top_level'] < 4 ? $num_heading[3].'.' : '' ).($toc_data['top_level'] < 5 ? $num_heading[4].'.' : '' ).($toc_data['top_level'] < 6 ? $num_heading[5].'.' : '' ).$num_heading[6].' ';
        break;
        default:
        $numerical = '';
      }
      
    }

    if($before_level === $currnt_level){
      echo '<li>'.esc_html(sanitize_text_field($numerical)).'<a href="'.esc_url(sanitize_text_field($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number)).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }else if ($currnt_level > $before_level ){

      while($currnt_level !== $before_level){
        echo '<ul><li>';
        $currnt_level-- ;
      }
      echo esc_html(sanitize_text_field($numerical)).'<a href="'.esc_url(sanitize_text_field($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number)).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }else{
      
      echo '<li>'.esc_html(sanitize_text_field($numerical)).'<a href="'.esc_url(sanitize_text_field($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number)).'">'.esc_html(wp_strip_all_tags($heading[2][$no])).'</a>';
    }
    $before_level = $now;

    if(isset($heading[1][$no + 1])) $after_level = (int)$heading[1][$no + 1];

    if($before_level === $after_level){
      echo '</li>'."\n";

    }else if($before_level > $after_level){

      $diff_level = $before_level - $after_level;
      while($diff_level > 0){
        echo'</li></ul></li>'."\n";
        $diff_level-- ;
      }

      echo ''."\n";
      

    }else{

      echo "\n";
      
    }

  }

  if ($before_level > $toc_data['top_level']){
    echo '</li>'."\n";
    $diff_level = $before_level;
    while($diff_level > 2){
      echo '</ul></li>'."\n";
      $diff_level-- ;
    }
  }

  echo '</ul>'."\n";


}

function toc_maker_widget_non_hierarchy($the_content,$heading, $is_numerical,$toc_data) {


  $ulol = $is_numerical ? 'ol' : 'ul';
  echo '<'.esc_attr(sanitize_text_field($ulol)).' class="toc_maker_ul'.($ulol === 'ol' ? ' toc_maker_ol' : '').'>';

  $heading_no[1] = $heading_no[2] = $heading_no[3] = $heading_no[4] = $heading_no[5] = $heading_no[6] = $num_heading[1] = $num_heading[2] = $num_heading[3] = $num_heading[4] = $num_heading[5] = $num_heading[6] = 0;

  foreach($heading[1] as $no => $now ){

    $now = (int)$now;
    $toc_data['page_permalink'][$no] = '';

    $heading_no[$now]++;
    $link_number = $heading_no[$now];


    $pattern = '{<h'.$now.'(.*?)>'.toc_maker_special_character_replace($heading[2][$no]).'<\/h'.$now.'>}isum';

    $replacement = '<h'.$now.'$1><span id="heading'.$now.'_'.$link_number.'">'.$heading[2][$no].'</span></h'.$now.'>';


    echo  '<li><a href="'.esc_url(sanitize_text_field($toc_data['page_permalink'][$no].'#heading'.$now.'_'.$link_number)).'">'.esc_html(sanitize_text_field($heading[2][$no])).'</a></li>'."\n";

  }
  echo  '</'.esc_attr(sanitize_text_field($ulol)).'>'."\n";

}