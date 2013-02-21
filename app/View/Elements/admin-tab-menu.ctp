<ul class="tab-menu">
    <?php foreach($links as $link) {
        // check if url is set
        if ( isset($link['url']) ) {
            $text = isset($link['text']) ? $link['text'] : null;
            $options = isset($link['options']) ? $link['options'] : null;
            // check if link selected, then set li class to selected
            if ( isset($options) && !strcmp('selected', $options['class']) ) {
                //$options['class'] = 'disabled'; // set link disabled (unclickable)
                echo '<li class="selected">' . $this->Html->link($text, $link['url'], $options) . '</li>' . "\n";
            } else {
                echo '<li>' . $this->Html->link($text, $link['url'], $options) . '</li>' . "\n";
            }
        } // else no url provided, echo nothing
    } ?>
<ul>
