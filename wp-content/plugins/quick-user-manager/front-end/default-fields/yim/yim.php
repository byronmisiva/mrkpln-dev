<?php
/* handle field output */
function qum_yim_handler( $output, $form_location, $field, $user_id, $field_check_errors, $request_data ){	
	$item_title = apply_filters( 'qum_'.$form_location.'_yim_item_title', qum_icl_t( 'plugin quick-user-manager-pro', 'default_field_'.$field['id'].'_title_translation', $field['field-title'] ) );
	$item_description = qum_icl_t( 'plugin quick-user-manager-pro', 'default_field_'.$field['id'].'_description_translation', $field['description'] );
	$input_value = '';
	if( $form_location == 'edit_profile' )
		$input_value = get_the_author_meta( 'yim', $user_id );
	
	if ( trim( $input_value ) == '' )
		$input_value = $field['default-value'];
		
	$input_value = ( isset( $request_data['yim'] ) ? trim( $request_data['yim'] ) : $input_value );
	
	if ( $form_location != 'back_end' ){
		$error_mark = ( ( $field['required'] == 'Yes' ) ? '<span class="qum-required" title="'.qum_required_field_error($field["field-title"]).'">*</span>' : '' );
					
		if ( array_key_exists( $field['id'], $field_check_errors ) )
			$error_mark = '<img src="'.QUM_PLUGIN_URL.'assets/images/pencil_delete.png" title="'.qum_required_field_error($field["field-title"]).'"/>';

        $output = '
			<label for="yim">'.$item_title.$error_mark.'</label>
			<input class="text-input" name="yim" maxlength="'. apply_filters( 'qum_maximum_character_length', 70 ) .'" type="text" class="default_field_yim" id="yim" value="'. esc_attr( wp_unslash( $input_value ) ) .'" />';
        if( !empty( $item_description ) )
            $output .= '<span class="qum-description-delimiter">'. $item_description .'</span>';

	}
		
	return apply_filters( 'qum_'.$form_location.'_yim', $output, $form_location, $field, $user_id, $field_check_errors, $request_data );
}
add_filter( 'qum_output_form_field_default-yahoo-im', 'qum_yim_handler', 10, 6 );


/* handle field validation */
function qum_check_yim_value( $message, $field, $request_data, $form_location ){
    if( $field['required'] == 'Yes' ){
        if( ( isset( $request_data['yim'] ) && ( trim( $request_data['yim'] ) == '' ) ) || !isset( $request_data['yim'] ) ){
            return qum_required_field_error($field["field-title"]);
        }
    }

    return $message;
}
add_filter( 'qum_check_form_field_default-yahoo-im', 'qum_check_yim_value', 10, 4 );

/* handle field save */
function qum_userdata_add_yim( $userdata, $global_request ){
	if ( isset( $global_request['yim'] ) )
		$userdata['yim'] = sanitize_text_field( trim( $global_request['yim'] ) );
	
	return $userdata;
}
add_filter( 'qum_build_userdata', 'qum_userdata_add_yim', 10, 2 );