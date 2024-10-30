/* Function to verify selection to reset options */
function verifyResetOptions(element)
{
    if (element.checked) {
        if (prompt('Are you sure you want to reset all of your options? To confirm, type the word "reset" into the box.') == 'reset' ) {
             document.getElementById('calendar_press_settings').submit();
        } else {
             element.checked = false;
        }
    }
}

/* Function to change tabs on the settings pages */
function calendar_press_show_tab(tab)
{
     /* Close Active Tab */
     activeTab = document.getElementById('active_tab').value;
     document.getElementById('calendar_press_box_' + activeTab).style.display = 'none';
     document.getElementById('calendar_press_' + activeTab).removeAttribute('class','calendar-press-selected');

     /* Open new Tab */
     document.getElementById('calendar_press_box_' + tab).style.display = 'block';
     document.getElementById('calendar_press_' + tab).setAttribute('class','calendar-press-selected');
     document.getElementById('active_tab').value = tab;
}

/* Function to display extra fields if use-signups is checked. */
function signup_box_click(selection)
{
     switch (selection) {
    case 3:
         document.getElementById('signup_extra_fields').style.display = '';
         document.getElementById('signup_yesno_fields').style.display = '';
               break;
    case 2:
         document.getElementById('signup_extra_fields').style.display = 'none';
         document.getElementById('signup_yesno_fields').style.display = '';
               break;
    case 1:
         document.getElementById('signup_extra_fields').style.display = '';
         document.getElementById('signup_yesno_fields').style.display = 'none';
               break;
    default:
         document.getElementById('signup_extra_fields').style.display = 'none';
         document.getElementById('signup_yesno_fields').style.display = 'none';
     }
}

/* Function to submit the settings from the tab */
function calendar_press_settings_submit()
{
     document.getElementById('calendar_press_settings').submit();
}

/* Set up the date fields */
jQuery(document).ready(
    function () {
        try{     // Set up date fields for event form //
            jQuery('input#beginDate').DatePicker(
                {
                    format:'m/d/Y',
                    date: jQuery('input#beginDate').val(),
                    current: jQuery('input#beginDate').val(),
                    starts: 0,
                    position: 'right',
                    onBeforeShow: function () {
                        jQuery('input#beginDate').DatePickerSetDate(jQuery('input#beginDate').val(), true);
                    },
                    onChange: function (formated, dates) {
                        jQuery('input#beginDate').val(formated);
                        jQuery('input#beginDate').DatePickerHide();
                        jQuery('input#endDate').val(formated);
                    }
                }
            );
        } catch(error) {}

        try{
             // Set up date fields for event form //
            jQuery('input#endDate').DatePicker(
                {
                    format:'m/d/Y',
                    date: jQuery('input#endDate').val(),
                    current: jQuery('input#endDate').val(),
                    starts: 0,
                    position: 'right',
                    onBeforeShow: function () {
                        jQuery('input#endDate').DatePickerSetDate(jQuery('input#endDate').val(), true);
                    },
                    onChange: function (formated, dates) {
                        jQuery('input#endDate').val(formated);
                        jQuery('input#endDate').DatePickerHide();
                    }
                }
            );
        } catch(error) {}
    }
);
