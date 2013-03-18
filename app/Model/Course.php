<?php

class Course extends AppModel {
	public $name = 'Course';

	public $hasMany = array(
        'CourseMembership' => array('dependent' => true),
        'Exercise' => array('dependent' => true),
        'Group' => array('dependent' => true)
    );

	public $hasAndBelongsToMany = array('User');

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Kurssin nimi on pakollinen'
            )
        ),
        'starttime' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Aloituspäivämäärä on pakollinen'
            ),
            'dateformat' => array(
                'rule' => array('datetime', 'dmy'),
                'message' => 'Tarkista päivämäärän muoto'
            )
        ),
        'endtime' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Loppumispäivämäärä on pakollinen'
            ),
            'dateformat' => array(
                'rule' => array('datetime', 'dmy'),
                'message' => 'Tarkista päivämäärän muoto (pp.kk.vvvv hh:mm)'
            )
        )
    );
    public function beforeSave($options = array()) {
        if ( !empty($this->data[$this->alias]['starttime']) && !empty($this->data[$this->alias]['endtime']) ) {
            $this->data[$this->alias]['starttime'] = $this->format_date($this->data[$this->alias]['starttime']);
            $this->data[$this->alias]['endtime'] = $this->format_date($this->data[$this->alias]['endtime']);
        }
        return true;
    }

    public function format_date($date) {
        $formatted_datetime = date_create_from_format('d.m.Y H:i', $date);
        $datetime_dbstring = date_format($formatted_datetime, 'Y-m-d H:i:sO');
        return $datetime_dbstring;
    }

    public function get_courses($cid) {
        if ($cid <= 0) {
            $params = array(
                'order' => array('Course.endtime DESC'),
                'fields' => array('Course.id', 'Course.name', 'Course.starttime', 'Course.endtime')
            );
        } else {
            $params = array(
                'order' => array('Course.endtime DESC'),
                'fields' => array('Course.id', 'Course.name', 'Course.starttime', 'Course.endtime'),
                'conditions' => array('Course.id' => $cid),
            );
        }
        $this->contain();
        return $this->find('all', $params);
    }

    /**
     * Return requested course. Possible to add contain parameters.
     * @return Course, or false if $cid was omitted
     */
    public function get_course($cid = 0, $contain = array()) {
        if ( $cid > 0 ) {
            $options = array('conditions' => array('Course.id' => $cid));
            if ( !empty($contain) ) {
                $options['contain'] = $contain;
            }
            return $this->find('first', $options);
        } else {
            return false;
        }
    }

    public function get_users($cid = 0, $contain = array()) {
        if ( $cid > 0 ) {
            $options = array(
                'conditions' => array('Course.id' => $cid),
                'contain' => array(
                    'User' => array(
                        'Group' => array(
                            'conditions' => array('Group.course_id' => $cid)
                        )
                    )
                )
            );

            if ( !empty($contain) ) {
                $options['contain'] = $contain;
            }
            return $this->find('first', $options);
        } else {
            return false;
        }
    }
}
?>
