/**
 * Javascript functions for CalendarPress.
 *
 * @package    CalendarPress
 * @subpackage templates
 * @author     GrandSlambert
 * @copyright  2009-2022
 * @access     public
 * @since      0.1
 */

function onClickYesNo(button, id)
{
     var mysack = new sack(CPAJAX.ajaxurl);

     mysack.execute = 1;
     mysack.method = 'POST';
     mysack.setVar("action", "event_registration");
     mysack.setVar("type", button);
     mysack.setVar("id", id);
     mysack.setVar("click_action", "yesno");
     mysack.onError = function () {
          alert('Ajax error in registration.')
     };
     mysack.runAJAX();

     return true;
}
function onClickRegister(type, id)
{
     var mysack = new sack(CPAJAX.ajaxurl);


     mysack.execute = 1;
     mysack.method = 'POST';
     mysack.setVar("action", "event_registration");
     mysack.setVar("type", type);
     mysack.setVar("id", id);
     mysack.setVar("click_action", "signup");
     mysack.onError = function () {
          alert('Ajax error in saving your registration.')
     };
     mysack.runAJAX();

     return true;
}

function onClickCancel(type, id)
{
     var mysack = new sack(CPAJAX.ajaxurl);


     mysack.execute = 1;
     mysack.method = 'POST';
     mysack.setVar("action", "event_registration");
     mysack.setVar("type", type);
     mysack.setVar("id", id);
     mysack.setVar("click_action", "delete");
     mysack.onError = function () {
          alert('Ajax error in canceling your registration.')
     };
     mysack.runAJAX();

     return true;
}

function onClickMove(type, id)
{
     var mysack = new sack(CPAJAX.ajaxurl);

     mysack.execute = 1;
     mysack.method = 'POST';
     mysack.setVar("action", "event_registration");
     mysack.setVar("type", type);
     mysack.setVar("id", id);
     mysack.setVar("click_action", "move");
     mysack.onError = function () {
          alert('Ajax error in moving your registration.')
     };
     mysack.runAJAX();

     return true;
}

function onClickWaiting(id)
{
     alert('Sorry, no more room. Check back later.');
}

function onSackSuccess(status, type, id, message)
{
     var theButton = document.getElementById('button_' + type);
     Encoder.EncodeType = "entity";
     theButton.value = status;

     /* Disable all buttons */
     document.getElementById('event_buttons').innerHTML = Encoder.htmlDecode(message);
}