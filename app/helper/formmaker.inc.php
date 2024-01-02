<?php

defined('DR_INDEX_AUTH') OR exit('No direct script access allowed');

function createForm($_array_main_attr)
{
	if (count($_array_main_attr) < 1) {
		exit('Error creating form!. Please fill attribute');
	}

	foreach ($_array_main_attr as $key => $value) {
		$_main_attr = '';
		foreach ($_array_main_attr as $key => $value) {
			$_main_attr .= strip_tags($key).' = "'.strip_tags($value).'" ';
		}
	}

	echo '<form role="form" '.$_main_attr.'>';
	echo '<div class="card-body">';
}

function createFormContent($_label, $_type, $_name, $_place_holder = '', $_is_required = true, $_default_value = '', $_autofocus = '')
{
	$_value = '';
	// set default value in edit mode
	if (!empty($_default_value)) {
		$_value = strip_tags($_default_value);
		if ($_type == 'text') {
			$_value = ' value="'.strip_tags($_default_value).'"';
		}
	}
	// set tag
	echo '<div class="form-group">';
    echo '<label for="exampleInputEmail1">'.$_label.'</label>';

    $required = '';
    if ($_is_required)
    {
        $required = 'required';
    }
   
	switch ($_type) {
		case 'textarea':
			echo '<textarea class="form-control" name="'.$_name.'" rows="3" placeholder="'.$_place_holder.'" required>'.$_value.'</textarea>';
			break;
		
		case 'password':
			echo '<input type="'.$_type.'" name="'.$_name.'" class="form-control" '.$_value.' id="exampleInputEmail1" placeholder="'.$_place_holder.'" required '.$_autofocus.'/>';
			break;
		
		default:
		echo '<input type="'.$_type.'" name="'.$_name.'" class="form-control" '.$_value.' id="exampleInputEmail1" placeholder="'.$_place_holder.'" '.$required.' '.$_autofocus.'/>';
			break;
	}

  	echo '</div>';
}

function createUploadArea($_label, $_name, $labelUpload = 'Choose file', $_edit = false)
{
	echo '<style>
	.hidden {
		display: none;
	}
	</style>';

	echo '<div class="form-group hidden" id="'. $_name . '">';
	echo '<label for="'. $_name .'">'.$_label.'</label>';
	echo '<div class="input-group">';
	echo '<div class="custom-file">';
	echo '<input type="file" class="custom-file-input" name="'.$_name.'Input" id="'. $_name .'Input">'; // name: $_name.'Input' -> e.g., reservationDocumentInput
	echo '<label class="custom-file-label" for="'. $_name .'Input">' . $labelUpload . '</label>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

function createSelect($_label, $_name, $_option, $_attr = '', $_default_value = '')
{
	echo '<div class="form-group">';
	echo '<label>'.$_label.'</label>';
	echo '<select id="'. $_name .'" class="form-control "'.$_attr.' name="'.$_name.'">';
	// echo '<option value="0">Pilih</option>';
	foreach ($_option as $value) {
		if (!empty($_default_value) AND ($_default_value == $value['value'])) {
			echo '<option value="'.$value['value'].'" selected>'.$value['label'].'</option>';	
		} else {
			echo '<option value="'.$value['value'].'">'.$value['label'].'</option>';	
		}
	}
	echo '</select>';
	echo '</div>';
}

function createDynamicSelect($_label, $_name)
{
	echo '<style>
	.error-message {
		color: #b9191b; /* Red color */
		font-size: 0.8rem; /* Slightly smaller than your form controls */
		margin-bottom: 0.5rem;
		padding: 5px;
		border: 1px solid #e74c3c; /* Light red border */
		border-radius: 4px;
		background-color: #fcf8f6; /* Lighten background for contrast */
	}
	
	#error-container {
		display: block; /* Ensure container is visible */
	}
	</style>';
	echo '<div class="form-group">';
	echo '<label>'.$_label.'</label>';
	echo '<select id="'. $_name .'" class="form-control" name="'.$_name.'">';
	echo '</select>';
	echo '</div>';
	echo '<div id="error-container" aria-live="polite";></div>';
}


function createDate($_label, $_name, $_attr)
{
	echo '<div class="form-group">';
	echo '<label>'.$_label.'</label>';
	echo '<input class="form-control" type="date" id="' . $_name . '" name="' . $_name . '" ' . $_attr . '">';
	echo '</div>';
}

function createPasswordShow($_labels, $_names, $js = '')
{
	createFormContent($_labels[0],'password', $_names[0], 'Password');
	createFormContent($_labels[1],'password', $_names[1], 'Re-type Password');

	if (is_callable($js)) $js();
}

function createFormButton($_label, $_type, $_name, $_opt_class = '')
{
	echo '<div class="card-footer">';
    echo '<button type="'.$_type.'" name="'.$_name.'" class="btn btn-primary float-right '.$_opt_class.'">'.$_label.'</button>';
    echo '</div>';
}

function createBlindIframe($name, $hidden = true)
{
	$hidden = ($hidden)?'class="d-none" ':'class="d-block w-100" ';
	echo '<iframe name="'.$name.'" '.$hidden.'></iframe>';
}

function createAnything($_label, $_str_tag)
{
	// set tag
	echo '<div class="form-group">';
    echo '<label for="exampleInputEmail1" class="d-block w-100">'.strip_tags($_label).'</label>';
	echo $_str_tag;
	echo '</div>';
}

function closeTag($_str_tag_name)
{
	echo '</'.$_str_tag_name.'>';
}
