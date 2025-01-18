<?php 

$conn=mysqli_connect('localhost','root','','gge');


function htmlSelect($name, $items, $selectedValue = '', $default = '')
{
    printf('<select name="%s" id="%s">' . "\n",
           $name, $name);

    if ($default != null)
    {
        printf('<option value="">%s</option>', $default);
    }

    foreach ($items as $value => $text)
    {
        printf('<option value="%s" %s>%s</option>' . "\n",
               $value,
               $value == $selectedValue ? 'selected="selected"' : '',
               $text);
    }
    
    echo "</select>\n";
}

// Print a <input type="text"> element.
function htmlInputText($name, $value = '', $maxlength = '')
{
    printf('<input type="text" name="%s" id="%s" value="%s" maxlength="%s" />' . "\n",
           $name, $name, $value, $maxlength);
}

// Print a <input type="password"> element.
function htmlInputPassword($name, $value = '', $maxlength = '')
{
    printf('<input type="password" name="%s" id="%s" value="%s" maxlength="%s" />' . "\n",
           $name, $name, $value, $maxlength);
}

function htmlInputDatetime($name,$value = ''){
    printf('<input type="datetime-local" name="%s" id="%s" value="%s" autocomplete="off" required>'
        ,$name,$name,$value);
}

// Print a <input type="hidden"> element.
function htmlInputHidden($name, $value = '')
{
    printf('<input type="hidden" name="%s" id="%s" value="%s" />' . "\n",
           $name, $name, $value);
}

// Print a group of <input type="radio"> elements.
function htmlRadioList($name, $items, $selectedValue = '', $break = false)
{
    foreach ($items as $value => $text)
    {
        printf('
            <input type="radio" name="%s" id="%s" value="%s" %s />
            <label for="%s">%s</label>' . "\n",
            $name, $value, $value,
            $value == $selectedValue ? 'checked="checked"' : '',
            $value, $text);

        if ($break)
            echo "<br />\n";
    }
}


function validateEventName($name)
{
    if ($name == null)
    {
        return 'Please enter <strong>Event Name</strong>.';
    }

}

function getEventName()
{
    return array(
        'CSGO' => 'Counter Strike',
        'WZRY' => 'Wang Zhe Rong Yao',
        'JCC' => 'Jin Chan Chan'
    );
}

$EVENTS = getEventName();
?>






