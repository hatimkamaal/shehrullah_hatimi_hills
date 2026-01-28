<?php

class UIService {

    public static function showOptions(array $options, $selected) {
        if( count($options) == 0 ) {
            return;
        }

        if( array_is_list($options) ) {
            foreach( $options as $option ) {
                $checked = $option === $selected ? 'selected' : '';              
                echo "<option value='$option' $checked>$option</option>";
            }                
        } else {
            foreach( $options as $value => $option ) {
                $checked = $value === $selected ? 'selected' : '';              
                echo "<option value='$value' $checked>$option</option>";
            } 
        }
    }
}