// 18 Feb 97 created Eric Krock (c) 1997 Netscape Communications Corporation
var reWhitespace = /^\s+$/;
var reLetter = /^[·ÈÌÛ˙Òa-z¡…Õ”⁄—A-Z]$/;
var reAlphabetic = /^[·ÈÌÛ˙Òa-z¡…Õ”⁄—A-Z]+$/;
var reAlphanumeric = /^[·ÈÌÛ˙Òa-z¡…Õ”⁄—A-Z0-9]+$/;
var reDigit = /^\d$/;
var reLetterOrDigit = /^([·ÈÌÛ˙Òa-z¡…Õ”⁄—A-Z]|\d)$/;
var reInteger = /^\d+$/;
var reSignedInteger = /^[+|-]?\d+$/;
var reFloat = /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/;
var reSignedFloat = /^(([+|-]?\d+(\.\d*)?)|([+|-]?(\d*\.)?\d+))$/;
var reEmail = /^.+\@.+\..+$/;
// VARIABLE DECLARATIONS
var digits = "0123456789";
var lowercaseLetters = "abcdefghijklmnopqrstuvwxyz·ÈÌÛ˙Ò";
var uppercaseLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ¡…Õ”⁄—";
// whitespace characters as defined by this sample code
var whitespace = " \t\n\r";
// decimal point character differs by language and culture
var decimalPointDelimiter = ".";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in US phone numbers
var validUSPhoneChars = digits + phoneNumberDelimiters;
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = digits + phoneNumberDelimiters + "+";
// non-digit characters which are allowed in 
// Social Security Numbers
var SSNDelimiters = "- ";
// characters which are allowed in Social Security Numbers
var validSSNChars = digits + SSNDelimiters;
// U.S. Social Security Numbers have 9 digits.
// They are formatted as 123-45-6789.
var digitsInSocialSecurityNumber = 9;
// U.S. phone numbers have 10 digits.
// They are formatted as 123 456 7890 or (123) 456-7890.
var digitsInUSPhoneNumber = 10;
// non-digit characters which are allowed in ZIP Codes
var ZIPCodeDelimiters = "-";
// our preferred delimiter for reformatting ZIP Codes
var ZIPCodeDelimeter = "-"
// characters which are allowed in Social Security Numbers
var validZIPCodeChars = digits + ZIPCodeDelimiters
// U.S. ZIP codes have 5 or 9 digits.
// They are formatted as 12345 or 12345-6789.
var digitsInZIPCode1 = 5
var digitsInZIPCode2 = 9
// non-digit characters which are allowed in credit card numbers
var creditCardDelimiters = " "
// CONSTANT STRING DECLARATIONS
// m is an abbreviation for "missing"
var mPrefix = " "
var mSuffix = "\n Este es un campo requerido, Por favor digitelo ahora.\n"
// p is an abbreviation for "prompt"
var pEntryPrompt = "Por favor digite "
// i is an abreviation for "invalid"
var iPrefix = "Ud. digito informaciÛn no v·lida. "
var iSuffix = "\n Este es un campo requerido, Por favor digitelo ahora.\n"

// Global variable defaultEmptyOK defines default return value 
// for many functions when they are passed the empty string. 
// By default, they will return defaultEmptyOK.
var defaultEmptyOK = false
function makeArray(n) {
   for (var i = 1; i <= n; i++) {
      this[i] = 0
   } 
   return this
}
var daysInMonth = makeArray(12);
daysInMonth[1] = 31;
daysInMonth[2] = 29;   // must programmatically check this
daysInMonth[3] = 31;
daysInMonth[4] = 30;
daysInMonth[5] = 31;
daysInMonth[6] = 30;
daysInMonth[7] = 31;
daysInMonth[8] = 31;
daysInMonth[9] = 30;
daysInMonth[10] = 31;
daysInMonth[11] = 30;
daysInMonth[12] = 31;
// Check whether string s is empty.
function isEmpty(s)
{   return ((s == null) || (s.length == 0))
}
// Returns true if string s is empty or 
// whitespace characters only.
function isWhitespace (s)
{   // Is s empty?
    return (isEmpty(s) || reWhitespace.test(s));
}
// Removes all characters which appear in regexp bag from string s.
// NOTES:
// 1) bag must be a regexp which matches single characters in isolation,
//    i.e. A or B or C or D or 1 or 2 ...
//    e.g. /\d/g  or /[a-zA-Z]/g
// 2) make sure to append the 'g' modifier (for global search & replace)
//    at the end of the regexp
//    e.g. /\d/g  or /[a-zA-Z]/g
function stripCharsInRE (s, bag)
{       return s.replace(bag, "")
}
// Removes all characters which appear in string bag from string s.
function stripCharsInBag (s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}
// Removes all characters which do NOT appear in string bag 
// from string s.
function stripCharsNotInBag (s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) != -1) returnString += c;
    }
    return returnString;
}
// Removes all whitespace characters from s.
// Global variable whitespace (see above)
// defines which characters are considered whitespace.
function stripWhitespace (s)
{   return stripCharsInBag (s, whitespace)
}
// charInString (CHARACTER c, STRING s)
//
// Returns true if single character c (actually a string)
// is contained within string s.
function charInString (c, s)
{   for (i = 0; i < s.length; i++)
    {   if (s.charAt(i) == c) return true;
    }
    return false
}
// Removes initial (leading) whitespace characters from s.
// Global variable whitespace (see above)
// defines which characters are considered whitespace.
function stripInitialWhitespace (s)
{   var i = 0;
    while ((i < s.length) && charInString (s.charAt(i), whitespace))
       i++;
    
    return s.substring (i, s.length);
}
// Removes initial (leading) 0 characters from s.
function stripInitialCeros (s)
{   var i = 0;
    while ((i < s.length) && charInString (s.charAt(i), '0'))
       i++;
    return s.substring (i, s.length);
}
// Returns true if character c is an English letter 
// (A .. Z, a..z).
function isLetter (c)
{   return reLetter.test(c)
}
// Returns true if character c is a digit 
// (0 .. 9).
function isDigit (c)
{   return reDigit.test(c)
}
// Returns true if character c is a letter or digit.
function isLetterOrDigit (c)
{   return reLetterOrDigit.test(c)
}
// isInteger (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if all characters in string s are numbers.
//
// Accepts non-signed integers only. Does not accept floating 
// point, exponential notation, etc.
//
// We don't use parseInt because that would accept a string
// with trailing non-numeric characters.
//
// By default, returns defaultEmptyOK if s is empty.
// There is an optional second argument called emptyOK.
// emptyOK is used to override for a single function call
//      the default behavior which is specified globally by
//      defaultEmptyOK.
// If emptyOK is false (or any value other than true), 
//      the function will return false if s is empty.
// If emptyOK is true, the function will return true if s is empty.
//
// EXAMPLE FUNCTION CALL:     RESULT:
// isInteger ("5")            true 
// isInteger ("")             defaultEmptyOK
// isInteger ("-5")           false
// isInteger ("", true)       true
// isInteger ("", false)      false
// isInteger ("5", false)     true
function isInteger (s)
{   var i;
    if (isEmpty(s)) 
       if (isInteger.arguments.length == 1) return defaultEmptyOK;
       else return (isInteger.arguments[1] == true);
    return reInteger.test(s)
}
// isSignedInteger (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if all characters are numbers; 
// first character is allowed to be + or - as well.
//
// Does not accept floating point, exponential notation, etc.
//
// We don't use parseInt because that would accept a string
// with trailing non-numeric characters.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
//
// EXAMPLE FUNCTION CALL:          RESULT:
// isSignedInteger ("5")           true 
// isSignedInteger ("")            defaultEmptyOK
// isSignedInteger ("-5")          true
// isSignedInteger ("+5")          true
// isSignedInteger ("", false)     false
// isSignedInteger ("", true)      true
function isSignedInteger (s)
{   if (isEmpty(s)) 
       if (isSignedInteger.arguments.length == 1) return defaultEmptyOK;
       else return (isSignedInteger.arguments[1] == true);
    
    else {
       return reSignedInteger.test(s)
    }
}
// isPositiveInteger (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if string s is an integer > 0.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isPositiveInteger (s)
{   var secondArg = defaultEmptyOK;
    if (isPositiveInteger.arguments.length > 1)
        secondArg = isPositiveInteger.arguments[1];
    // The next line is a bit byzantine.  What it means is:
    // a) s must be a signed integer, AND
    // b) one of the following must be true:
    //    i)  s is empty and we are supposed to return true for
    //        empty strings
    //    ii) this is a positive, not negative, number
    return (isSignedInteger(s, secondArg)
         && ( (isEmpty(s) && secondArg)  || (parseInt (s) > 0) ) );
}
// isNonnegativeInteger (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if string s is an integer >= 0.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isNonnegativeInteger (s)
{   var secondArg = defaultEmptyOK;
    if (isNonnegativeInteger.arguments.length > 1)
        secondArg = isNonnegativeInteger.arguments[1];
    // The next line is a bit byzantine.  What it means is:
    // a) s must be a signed integer, AND
    // b) one of the following must be true:
    //    i)  s is empty and we are supposed to return true for
    //        empty strings
    //    ii) this is a number >= 0
    return (isSignedInteger(s, secondArg)
         && ( (isEmpty(s) && secondArg)  || (parseInt (s) >= 0) ) );
}
// isNegativeInteger (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if string s is an integer < 0.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isNegativeInteger (s)
{   var secondArg = defaultEmptyOK;
    if (isNegativeInteger.arguments.length > 1)
        secondArg = isNegativeInteger.arguments[1];
    // The next line is a bit byzantine.  What it means is:
    // a) s must be a signed integer, AND
    // b) one of the following must be true:
    //    i)  s is empty and we are supposed to return true for
    //        empty strings
    //    ii) this is a negative, not positive, number
    return (isSignedInteger(s, secondArg)
         && ( (isEmpty(s) && secondArg)  || (parseInt (s) < 0) ) );
}
// isNonpositiveInteger (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if string s is an integer <= 0.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isNonpositiveInteger (s)
{   var secondArg = defaultEmptyOK;
    if (isNonpositiveInteger.arguments.length > 1)
        secondArg = isNonpositiveInteger.arguments[1];
    // The next line is a bit byzantine.  What it means is:
    // a) s must be a signed integer, AND
    // b) one of the following must be true:
    //    i)  s is empty and we are supposed to return true for
    //        empty strings
    //    ii) this is a number <= 0
    return (isSignedInteger(s, secondArg)
         && ( (isEmpty(s) && secondArg)  || (parseInt (s) <= 0) ) );
}
// isFloat (STRING s [, BOOLEAN emptyOK])
// 
// True if string s is an unsigned floating point (real) number. 
//
// Also returns true for unsigned integers. If you wish
// to distinguish between integers and floating point numbers,
// first call isInteger, then call isFloat.
//
// Does not accept exponential notation.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isFloat (s)
{   if (isEmpty(s)) 
       if (isFloat.arguments.length == 1) return defaultEmptyOK;
       else return (isFloat.arguments[1] == true);
    return reFloat.test(s)
}
// isSignedFloat (STRING s [, BOOLEAN emptyOK])
// 
// True if string s is a signed or unsigned floating point 
// (real) number. First character is allowed to be + or -.
//
// Also returns true for unsigned integers. If you wish
// to distinguish between integers and floating point numbers,
// first call isSignedInteger, then call isSignedFloat.
//
// Does not accept exponential notation.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isSignedFloat (s)
{   if (isEmpty(s)) 
       if (isSignedFloat.arguments.length == 1) return defaultEmptyOK;
       else return (isSignedFloat.arguments[1] == true);
    else {
       return reSignedFloat.test(s)
    }
}
// isAlphabetic (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if string s is English letters 
// (A .. Z, a..z) only.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
//
// NOTE: Need i18n version to support European characters.
// This could be tricky due to different character
// sets and orderings for various languages and platforms.
function isAlphabetic (s)
{   var i;
    if (isEmpty(s)) 
       if (isAlphabetic.arguments.length == 1) return defaultEmptyOK;
       else return (isAlphabetic.arguments[1] == true);
    else {
       return reAlphabetic.test(s)
    }
}
// isAlphanumeric (STRING s [, BOOLEAN emptyOK])
// 
// Returns true if string s is English letters 
// (A .. Z, a..z) and numbers only.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
//
// NOTE: Need i18n version to support European characters.
// This could be tricky due to different character
// sets and orderings for various languages and platforms.
function isAlphanumeric (s)
{   var i;
    if (isEmpty(s)) 
       if (isAlphanumeric.arguments.length == 1) return defaultEmptyOK;
       else return (isAlphanumeric.arguments[1] == true);
    else {
       return reAlphanumeric.test(s)
    }
}
// reformat (TARGETSTRING, STRING, INTEGER, STRING, INTEGER ... )       
//
// Handy function for arbitrarily inserting formatting characters
// or delimiters of various kinds within TARGETSTRING.
//
// reformat takes one named argument, a string s, and any number
// of other arguments.  The other arguments must be integers or
// strings.  These other arguments specify how string s is to be
// reformatted and how and where other strings are to be inserted
// into it.
//
// reformat processes the other arguments in order one by one.
// * If the argument is an integer, reformat appends that number 
//   of sequential characters from s to the resultString.
// * If the argument is a string, reformat appends the string
//   to the resultString.
//
// NOTE: The first argument after TARGETSTRING must be a string.
// (It can be empty.)  The second argument must be an integer.
// Thereafter, integers and strings must alternate.  This is to
// provide backward compatibility to Navigator 2.0.2 JavaScript
// by avoiding use of the typeof operator.
//
// It is the caller's responsibility to make sure that we do not
// try to copy more characters from s than s.length.
//
// EXAMPLES:
//
// * To reformat a 10-digit U.S. phone number from "1234567890"
//   to "(123) 456-7890" make this function call:
//   reformat("1234567890", "(", 3, ") ", 3, "-", 4)
//
// * To reformat a 9-digit U.S. Social Security number from
//   "123456789" to "123-45-6789" make this function call:
//   reformat("123456789", "", 3, "-", 2, "-", 4)
//
// HINT:
//
// If you have a string which is already delimited in one way
// (example: a phone number delimited with spaces as "123 456 7890")
// and you want to delimit it in another way using function reformat,
// call function stripCharsNotInBag to remove the unwanted 
// characters, THEN call function reformat to delimit as desired.
//
// EXAMPLE:
//
// reformat (stripCharsNotInBag ("123 456 7890", digits),
//           "(", 3, ") ", 3, "-", 4)
function reformat (s)
{   var arg;
    var sPos = 0;
    var resultString = "";
    for (var i = 1; i < reformat.arguments.length; i++) {
       arg = reformat.arguments[i];
       if (i % 2 == 1) resultString += arg;
       else {
           resultString += s.substring(sPos, sPos + arg);
           sPos += arg;
       }
    }
    return resultString;
}
// isSSN (STRING s [, BOOLEAN emptyOK])
// 
// isSSN returns true if string s is a valid U.S. Social
// Security Number.  Must be 9 digits.
//
// NOTE: Strip out any delimiters (spaces, hyphens, etc.)
// from string s before calling this function.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isSSN (s)
{   if (isEmpty(s)) 
       if (isSSN.arguments.length == 1) return defaultEmptyOK;
       else return (isSSN.arguments[1] == true);
    return (isInteger(s) && s.length == digitsInSocialSecurityNumber)
}
// isUSPhoneNumber (STRING s [, BOOLEAN emptyOK])
// 
// isUSPhoneNumber returns true if string s is a valid U.S. Phone
// Number.  Must be 10 digits.
//
// NOTE: Strip out any delimiters (spaces, hyphens, parentheses, etc.)
// from string s before calling this function.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isUSPhoneNumber (s)
{   if (isEmpty(s)) 
       if (isUSPhoneNumber.arguments.length == 1) return defaultEmptyOK;
       else return (isUSPhoneNumber.arguments[1] == true);
    return (isInteger(s) && s.length == digitsInUSPhoneNumber)
}
// isInternationalPhoneNumber (STRING s [, BOOLEAN emptyOK])
// 
// isInternationalPhoneNumber returns true if string s is a valid 
// international phone number.  Must be digits only; any length OK.
// May be prefixed by + character.
//
// NOTE: A phone number of all zeros would not be accepted.
// I don't think that is a valid phone number anyway.
//
// NOTE: Strip out any delimiters (spaces, hyphens, parentheses, etc.)
// from string s before calling this function.  You may leave in 
// leading + character if you wish.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isInternationalPhoneNumber (s)
{   if (isEmpty(s)) 
       if (isInternationalPhoneNumber.arguments.length == 1) return defaultEmptyOK;
       else return (isInternationalPhoneNumber.arguments[1] == true);
    return (isPositiveInteger(s))
}
// isZIPCode (STRING s [, BOOLEAN emptyOK])
// 
// isZIPCode returns true if string s is a valid 
// U.S. ZIP code.  Must be 5 or 9 digits only.
//
// NOTE: Strip out any delimiters (spaces, hyphens, etc.)
// from string s before calling this function.  
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isZIPCode (s)
{  if (isEmpty(s)) 
       if (isZIPCode.arguments.length == 1) return defaultEmptyOK;
       else return (isZIPCode.arguments[1] == true);
   return (isInteger(s) && 
            ((s.length == digitsInZIPCode1) ||
             (s.length == digitsInZIPCode2)))
}
// isEmail (STRING s [, BOOLEAN emptyOK])
// 
// Email address must be of form a@b.c -- in other words:
// * there must be at least one character before the @
// * there must be at least one character before and after the .
// * the characters @ and . are both required
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isEmail (s)
{   if (isEmpty(s)) 
       if (isEmail.arguments.length == 1) return defaultEmptyOK;
       else return (isEmail.arguments[1] == true);
    
    else {
       return reEmail.test(s)
    }
}
// isYear (STRING s [, BOOLEAN emptyOK])
// 
// isYear returns true if string s is a valid 
// Year number.  Must be 2 or 4 digits only.
// 
// For Year 2000 compliance, you are advised
// to use 4-digit year numbers everywhere.
//
// And yes, this function is not Year 10000 compliant, but 
// because I am giving you 8003 years of advance notice,
// I don't feel very guilty about this ...
//
// For B.C. compliance, write your own function. ;->
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isYear (s)
{   if (isEmpty(s)) 
       if (isYear.arguments.length == 1) return defaultEmptyOK;
       else return (isYear.arguments[1] == true);
    if (!isNonnegativeInteger(s)) return false;
    return ((s.length == 2) || (s.length == 4));
}
// isIntegerInRange (STRING s, INTEGER a, INTEGER b [, BOOLEAN emptyOK])
// 
// isIntegerInRange returns true if string s is an integer 
// within the range of integer arguments a and b, inclusive.
// 
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isIntegerInRange (s, a, b)
{   if (isEmpty(s)) 
       if (isIntegerInRange.arguments.length == 1) return defaultEmptyOK;
       else return (isIntegerInRange.arguments[1] == true);
    if (!isInteger(s, false)) return false;
    var num = parseInt (s);
    return ((num >= a) && (num <= b));
}
// isMonth (STRING s [, BOOLEAN emptyOK])
// 
// isMonth returns true if string s is a valid 
// month number between 1 and 12.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isMonth (s)
{   if (isEmpty(s)) 
       if (isMonth.arguments.length == 1) return defaultEmptyOK;
       else return (isMonth.arguments[1] == true);
    return isIntegerInRange (stripInitialCeros (s), 1, 12);
}
// isDay (STRING s [, BOOLEAN emptyOK])
// 
// isDay returns true if string s is a valid 
// day number between 1 and 31.
// 
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function isDay (s)
{   if (isEmpty(s)) 
       if (isDay.arguments.length == 1) return defaultEmptyOK;
       else return (isDay.arguments[1] == true);   
    return isIntegerInRange (stripInitialCeros (s), 1, 31);
}
// daysInFebruary (INTEGER year)
// 
// Given integer argument year,
// returns number of days in February of that year.
function daysInFebruary (year)
{   // February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (  ((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0) ) ) ? 29 : 28 );
}
// isDate (STRING year, STRING month, STRING day)
//
// isDate returns true if string arguments year, month, and day 
// form a valid date.
// 
function isDate (year, month, day)
{   // catch invalid years (not 2- or 4-digit) and invalid months and days.
    if (! (isYear(year, false) && isMonth(month, false) && isDay(day, false))) return false;
    var intYear = parseInt(year);
    var intMonth = parseInt(month);
    var intDay = parseInt(day);
    if (intDay > daysInMonth[intMonth]) return false; 
    if ((intMonth == 2) && (intDay > daysInFebruary(intYear))) return false;
    return true;
}
// Get checked value from radio button.
function getRadioButtonValue (theField)
{   for (var i = 0; i < theField.length; i++)
    {   if (theField[i].checked) {
            return theField[i].value
        }
    }
    return false
}
/* FUNCTIONS TO NOTIFY USER OF INPUT REQUIREMENTS OR MISTAKES. */
// Display prompt string s in status bar.
function prompt (s)
{   window.status = s
}
// Display data entry prompt string s in status bar.
function promptEntry (s)
{   window.status = pEntryPrompt + s
}
// Notify user that required field theField is empty.
// String s describes expected contents of theField.value.
// Put focus in theField and return false.
function warnEmpty (theField, s)
{   
    alert(mPrefix + s + mSuffix)
    theField.focus()
    return false
}
// Notify user that contents of field theField are invalid.
// String s describes expected contents of theField.value.
// Put select theField, pu focus in it, and return false.
function warnInvalid (theField, s)
{   
    alert(iPrefix + s + iSuffix)
    //theField.focus()
	if(theField.type == "text") {
    	theField.select()	
	}
    return false
}

/* FUNCTIONS TO INTERACTIVELY CHECK VARIOUS FIELDS. */
// checkString (TEXTFIELD theField, STRING s, [, BOOLEAN emptyOK==false])
//
// Check that string theField.value is not all whitespace.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function checkString (theField, s, emptyOK)
{   // Next line is needed on NN3 to avoid "undefined is not a number" error
    // in equality comparison below.
    if (checkString.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (isWhitespace(theField.value)) 
       return warnEmpty (theField, s);
    else return true;
}
// checkInteger (TEXTFIELD theField, STRING s, [, BOOLEAN emptyOK==false])
//
// Check that string theField.value is integer.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function checkInteger (theField, s, emptyOK)
{   // Next line is needed on NN3 to avoid "undefined is not a number" error
    // in equality comparison below.
     if(theField.value < 0 && emptyOK==false)
	 return warnInvalid(theField, s)
	if (checkInteger.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (!isInteger(theField.value, false)) 
       return warnInvalid(theField, s);
    else return true;
}



function checkFloat (theField, s, emptyOK)
{   // Next line is needed on NN3 to avoid "undefined is not a number" error
    // in equality comparison below.

  if(theField.value < 0 && emptyOK==false)
	 return warnInvalid(theField, s)
	
	if (checkFloat.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (!isFloat(theField.value, false)) 
       return warnInvalid(theField, s);
    else return true;
}

// checkEmail (TEXTFIELD theField [, BOOLEAN emptyOK==false])
//
// Check that string theField.value is a valid Email.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function checkEmail (theField, s, emptyOK)
{   if (checkEmail.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    else if (!isEmail(theField.value, false)) 
       return warnInvalid (theField, s);
    else return true;
}
// Check that string theField.value is a valid Year.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function checkYear (theField, s, emptyOK)
{   if (checkYear.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (!isYear(theField.value, false)) 
       return warnInvalid (theField, s);
    else return true;
}
// Check that string theField.value is a valid Month.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function checkMonth (theField, s, emptyOK)
{   if (checkMonth.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (!isMonth(theField.value, false)) 
       return warnInvalid (theField, s);
    else return true;
}
// Check that string theField.value is a valid Day.
//
// For explanation of optional argument emptyOK,
// see comments of function isInteger.
function checkDay (theField, s, emptyOK)
{   if (checkDay.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (!isDay(theField.value, false)) 
       return warnInvalid (theField, s);
    else return true;
}
// checkDate (theField, s,)
//
// Check that theField.value 
// form a valid date.
// format is dd/mm/yyyy
function checkDate (theField, s, emptyOK)
{   
    if (checkDate.arguments.length == 2) emptyOK = defaultEmptyOK;
    if ((emptyOK == true) && (isEmpty(theField.value))) return true;
    if (theField.value.length <= 8) return warnInvalid (theField, s);
    
	var regExpDate = new RegExp("[0-9][0-9]/[0-9][0-9]/[0-9][0-9][0-9][0-9]");
	if (regExpDate.test(theField.value) == false) {
		return warnInvalid (theField, 'La fecha no tiene un fomato valido.');
	}
	
    var myDateArray = theField.value.split("/");

    if (!isDate (myDateArray[2], myDateArray[1], myDateArray[0])) {
		   return warnInvalid (theField, s);
	}
    return true;    
}

//Checks that a value has been selected within a list of checkbox fields with the same name.
function checkCheckboxList(theForm, theField, s)
{   
    for(i=0; i< theForm.length; i++)
    {
        var e = 0;
        var returnValue = false;
        
        e = theForm.elements[i];
        if (e.type=='checkbox' && e.name == theField.name)
        {
            if (theField.checked) {
                returnValue = true;
            }
        }
    }
 
    if (returnValue)
        { 
            return true;
        }
        
    return warnEmpty (theField, s);
}

//Checks that a value has been selected with checkbox field.
function checkCheckbox(theField, s)
{   
    if (theField.checked)
        { 
            return true;
        }
    return warnEmpty (theField, s);
}

//Checks that a value has been selected with radio field elements.
function checkRadio(theField, s)
{   
	var len;
	len = parseInt(theField.length);
	if (isNaN(len)) { return (theField.checked) }
	for(var n = 0; n < len; n++)
    {   if(theField[n].checked)
        { return true;
        }
    }
    return warnInvalid (theField, s);
}
//Checks that a value has been selected with dropdown list field elements.
function checkList(theField, s)
{
	var len;
	len = parseInt(theField.length);
	if (isNaN(len)) { return false; }
    
    for (var n = 1; n < len; n++){   
        if ((theField.options[n].selected) && (!isEmpty(theField.options[n].value))){
            return true;
        }
    }
    return warnInvalid (theField, s);
}
// Validate credit card info.
function checkCreditCard (theRadioField, theField, s)
{   var cardType = getRadioButtonValue (theRadioField)
    var normalizedCCN = stripCharsInBag(theField.value, creditCardDelimiters)
    if (!isCardMatch(cardType, normalizedCCN)) 
       return warnInvalid (theField, s);
    else 
    {  theField.value = normalizedCCN
       return true
    }
}
/*  ================================================================
    Credit card verification functions
    Originally included as Starter Application 1.0.0 in LivePayment.
    20 Feb 1997 modified by egk:
           changed naming convention to initial lowercase
                  (isMasterCard instead of IsMasterCard, etc.)
           changed isCC to isCreditCard
           retained functions named with older conventions from
                  LivePayment as stub functions for backward 
                  compatibility only
           added "AMERICANEXPRESS" as equivalent of "AMEX" 
                  for naming consistency 
    ================================================================ */
/*  ================================================================
    FUNCTION:  isCreditCard(st)
 
    INPUT:     st - a string representing a credit card number
    RETURNS:  true, if the credit card number passes the Luhn Mod-10
		    test.
	      false, otherwise
    ================================================================ */
function isCreditCard(st) {
  // Encoding only works on cards with less than 19 digits
  if (st.length > 19)
    return (false);
  sum = 0; mul = 1; l = st.length;
  for (i = 0; i < l; i++) {
    digit = st.substring(l-i-1,l-i);
    tproduct = parseInt(digit ,10)*mul;
    if (tproduct >= 10)
      sum += (tproduct % 10) + 1;
    else
      sum += tproduct;
    if (mul == 1)
      mul++;
    else
      mul--;
  }
  if ((sum % 10) == 0)
    return (true);
  else
    return (false);
} // END FUNCTION isCreditCard()
/*  ================================================================
    FUNCTION:  isVisa()
 
    INPUT:     cc - a string representing a credit card number
    RETURNS:  true, if the credit card number is a valid VISA number.
		    
	      false, otherwise
    Sample number: 4111 1111 1111 1111 (16 digits)
    ================================================================ */
function isVisa(cc)
{
  if (((cc.length == 16) || (cc.length == 13)) &&
      (cc.substring(0,1) == 4))
    return isCreditCard(cc);
  return false;
}  // END FUNCTION isVisa()
/*  ================================================================
    FUNCTION:  isMasterCard()
 
    INPUT:     cc - a string representing a credit card number
    RETURNS:  true, if the credit card number is a valid MasterCard
		    number.
		    
	      false, otherwise
    Sample number: 5500 0000 0000 0004 (16 digits)
    ================================================================ */
function isMasterCard(cc)
{
  firstdig = cc.substring(0,1);
  seconddig = cc.substring(1,2);
  if ((cc.length == 16) && (firstdig == 5) &&
      ((seconddig >= 1) && (seconddig <= 5)))
    return isCreditCard(cc);
  return false;
} // END FUNCTION isMasterCard()
/*  ================================================================
    FUNCTION:  isAmericanExpress()
 
    INPUT:     cc - a string representing a credit card number
    RETURNS:  true, if the credit card number is a valid American
		    Express number.
		    
	      false, otherwise
    Sample number: 340000000000009 (15 digits)
    ================================================================ */
function isAmericanExpress(cc)
{
  firstdig = cc.substring(0,1);
  seconddig = cc.substring(1,2);
  if ((cc.length == 15) && (firstdig == 3) &&
      ((seconddig == 4) || (seconddig == 7)))
    return isCreditCard(cc);
  return false;
} // END FUNCTION isAmericanExpress()
/*  ================================================================
    FUNCTION:  isDinersClub()
 
    INPUT:     cc - a string representing a credit card number
    RETURNS:  true, if the credit card number is a valid Diner's
		    Club number.
		    
	      false, otherwise
    Sample number: 30000000000004 (14 digits)
    ================================================================ */
function isDinersClub(cc)
{
  firstdig = cc.substring(0,1);
  seconddig = cc.substring(1,2);
  if ((cc.length == 14) && (firstdig == 3) &&
      ((seconddig == 0) || (seconddig == 6) || (seconddig == 8)))
    return isCreditCard(cc);
  return false;
}
/*  ================================================================
    FUNCTION:  isAnyCard()
 
    INPUT:     cc - a string representing a credit card number
    RETURNS:  true, if the credit card number is any valid credit
		    card number for any of the accepted card types.
		    
	      false, otherwise
    ================================================================ */
function isAnyCard(cc)
{
  if (!isCreditCard(cc))
    return false;
  if (!isMasterCard(cc) && !isVisa(cc) && !isAmericanExpress(cc) && !isDinersClub(cc)) {
    return false;
  }
  return true;
} // END FUNCTION isAnyCard()
/*  ================================================================
    FUNCTION:  isCardMatch()
 
    INPUT:    cardType - a string representing the credit card type
	      cardNumber - a string representing a credit card number
    RETURNS:  true, if the credit card number is valid for the particular
	      credit card type given in "cardType".
		    
	      false, otherwise
    ================================================================ */
function test(){
	alert()
}
function validarFormatoFecha(fecha){
 
	mesFecha	=fecha.substring (0, 2);
	diaFecha	=fecha.substring (3, 5);
	anioFecha	=fecha.substring (6, 10);
	
	if(mesFecha/1 > 12){
		alert('Formato  de fecha incorrecto\n Mes '+ mesFecha+' no valido ,el formato debe ser mm/dd/aaaa')
       	return false 
	}
	if(diaFecha/1 > 31){
		alert('Formato  de fecha incorrecto\n Dia '+ diaFecha+' no valido ,el formato debe ser mm/dd/aaaa')
       	return false 
	}
	
	if (isNaN(anioFecha)||isNaN(mesFecha)||isNaN(diaFecha)){ 
	 	alert('Formato  de fecha incorrecto\n Valores no numÈricos encontrados ,el formato debe ser mm/dd/aaaa')
       	return false 
 	}
	
	if (fecha.length!=10) {
		alert('Formato  de fecha incorrecto\n Fecha Incompleta,el formato debe ser mm/dd/aaaa')
		return false 
	}
	return true
}
function validarFecha(fecha,validarDias,validarMes,validaMayor){
 	if(isEmpty(validaMayor)){
		validaMayor=true;
	} 
	fecha=fecha.value;
 	anioFecha	=fecha.substring (0, 4);
	mesFecha	=fecha.substring (4, 6);
	diaFecha	=fecha.substring (6, 8);
	
	if(mesFecha/1<=0 || mesFecha/1 >12 ){
		//alert('Por favor indique un mes valido\n Entre 1-12')
       //	return false 
	}	
	if(diaFecha/1==0){
		//alert('Por favor indique un dia valido\n Entre 1-31')
       //	return false 
	}	
	 if (isNaN(anioFecha)||isNaN(mesFecha)||isNaN(diaFecha)){ 
	 //	alert('la fecha tiene un formato no valido')
       //	return false 
 	}
	fechaCompleta=anioFecha+mesFecha+diaFecha;
	if (fechaCompleta.length!=8) {
		//alert('Por favor complete los digitos necesarios para la fecha')
		//return false 
	}
    fechaHoy=new Date();
	anios=fechaHoy.getYear()- anioFecha ;  
	if(anios != 0){
		//alert('El AÒo debe ser el actualy '+fechaHoy.getYear());
		//return false; 
	}
	mesActual=(fechaHoy.getMonth()/1)+ 1
	mes=mesActual- mesFecha ; 
	if(mes != 0 && validarMes){
		//alert('El Mes debe ser el actual '+ mesActual);
		//return false; 
	}
	
	dia=fechaHoy.getUTCDate()- (diaFecha/1)	; 
	if(validaMayor){
		if(dia < 0 ){
			//alert('La Fecha no puede ser posterior a Hoy ');
			//return false; 
		}
	}	
	if(dia > 2 && validarDias){
		//alert('La Fecha No puede ser inferior  de 2 dias de la fecha actual ');
		//return false; 
	}  
	

	
	 return true;  	
}
function isCardMatch (cardType, cardNumber)
{
	cardType = cardType.toUpperCase();
	var doesMatch = true;
	if ((cardType == "VISA") && (!isVisa(cardNumber)))
		doesMatch = false;
	if ((cardType == "MASTERCARD") && (!isMasterCard(cardNumber)))
		doesMatch = false;
	if ( ( (cardType == "AMERICANEXPRESS") || (cardType == "AMEX") )
                && (!isAmericanExpress(cardNumber))) doesMatch = false;
	if ((cardType == "DISCOVER") && (!isDiscover(cardNumber)))
		doesMatch = false;
	return doesMatch;
}  // END FUNCTION CardMatch()
