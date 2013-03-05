<?php

class Exercise extends AppModel {
    public $name = 'Exercise';
    
    public $belongsTo = array('Course');
    public $hasAndBelongsToMany = array('Action');

    public $validate = array(
        'exercise_number' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Harjoitusnumero vaaditaan'
            ),
            'number' => array(
                'rule' => 'numeric',
                'message' => 'Harjoitusnumeron täytyy olla numero'
            )
        ),
        'exercise_name' => array(
            'required' => array(
                'rule' => array('notEmpty'), 
                'message' => 'Anna harjoituksen nimi'
            )
        ),
        'starttime' => array(
            'required' => array(
                'rule' => array(
                    'notEmpty'
                ), 
                'message' => 'Anna harjoituksen alkuajankohta'
            ),
            'dateformat' => array(
                'rule' => array('datetime', 'dmy'),
                'message' => 'Tarkista päivämäärän muoto (pp.kk.vvvv hh:mm)'
            )
        ),
        'endtime' => array(
            'required' => array(
                'rule' => array(
                    'notEmpty'
                ), 
                'message' => 'Anna harjoituksen loppuajankohta'
            ),
            'dateformat' => array(
                'rule' => array('datetime', 'dmy'),
                'message' => 'Tarkista päivämäärän muoto (pp.kk.vvvv hh:mm)'
            )
        ),
        'review_starttime' => array(
            'required' => array(
                'rule' => array(
                    'notEmpty'
                ), 
                'message' => 'Harjoituksen arviointiaika vaaditaan'
            ),
            'dateformat' => array(
                'rule' => array('datetime', 'dmy'),
                'message' => 'Tarkista päivämäärän muoto (pp.kk.vvvv hh:mm)'
            )
        ),
        'review_endtime' => array(
            'required' => array(
                'rule' => array(
                    'notEmpty'
                ), 
                'message' => 'Harjoituksen arviointiaika vaaditaan'
            ),
            'dateformat' => array(
                'rule' => array('datetime', 'dmy'),
                'message' => 'Tarkista päivämäärän muoto (pp.kk.vvvv hh:mm)'
            )
        )
    );

    public function beforeSave() {
        if ( !empty($this->data[$this->alias]['starttime']) ) {
            $this->data[$this->alias]['starttime'] = $this->format_date($this->data[$this->alias]['starttime']);
        }
        if ( !empty($this->data[$this->alias]['endtime']) ) {
            $this->data[$this->alias]['endtime'] = $this->format_date($this->data[$this->alias]['endtime']);
        }
        if ( !empty($this->data[$this->alias]['review_starttime']) ) {
            $this->data[$this->alias]['review_starttime'] = $this->format_date($this->data[$this->alias]['review_starttime']);
        }
        if ( !empty($this->data[$this->alias]['review_endtime']) ) {
            $this->data[$this->alias]['review_endtime'] = $this->format_date($this->data[$this->alias]['review_endtime']);
        }
        return true;
    }

    public function format_date($date) {
        $formatted_datetime = date_create_from_format('d.m.Y H:i', $date);
        $datetime_dbstring = date_format($formatted_datetime, 'Y-m-d H:i:sO');
        return $datetime_dbstring;
    }

    public function compareDate($start, $end) {
        return strtotime($start) < strtotime($end);
    }

    // Virtual field used in find('list') operations to
    // get proper options for html form <select>-tag
    public $virtualFields = array(
        'exercise_string' => '\'H\' || Exercise.exercise_number || \': \'  || Exercise.exercise_name'
    );
}
?>