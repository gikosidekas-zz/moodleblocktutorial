<?php

class block_simplehtml extends block_base {

    public function init() {
        $this->title = get_string('simplehtml', 'block_simplehtml');
    }

    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
    	

    	$this->content         =  new stdClass;
	    $this->content->text   = 'The content of our SimpleHTML block!';
    	global $COURSE, $DB; //$COURSE should already be present

    	$url = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
		$this->content->footer = html_writer::link($url, get_string('addpage', 'block_simplehtml'));
    	
	    if ($this->content !== null) {
	      return $this->content;
	    }

	    if (!empty($this->config->text)) {
		    $this->content->text = $this->config->text;
		}

	    // This is the new code.
		if ($simplehtmlpages = $DB->get_records('block_simplehtml', array('blockid' => $this->instance->id))) {
		    $this->content->text .= html_writer::start_tag('ul');
		    foreach ($simplehtmlpages as $simplehtmlpage) {
		        $pageurl = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $simplehtmlpage->id, 'viewpage' => '1'));
		        $this->content->text .= html_writer::start_tag('li');
		        $this->content->text .= html_writer::link($pageurl, $simplehtmlpage->pagetitle);
		        $this->content->text .= html_writer::end_tag('li');
		    }
		    $this->content->text .= html_writer::end_tag('ul');
		}
	 
		// The other code.
	 

	    return $this->content;
	}

	public function instance_allow_multiple() {
	  	return true;
	}

	public function instance_config_save($data,$nolongerused =false) {
	  if(get_config('simplehtml', 'Allow_HTML') == '1') {
	    $data->text = strip_tags($data->text);
	  }
	 
	  // And now forward to the default implementation defined in the parent class
	  return parent::instance_config_save($data,$nolongerused);
	}

	public function hide_header() {
	  return true;
	}

	public function html_attributes() {
	    $attributes = parent::html_attributes(); // Get default values
	    $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
	    return $attributes;
	}

}

