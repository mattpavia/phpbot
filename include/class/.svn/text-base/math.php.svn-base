<?php
   /**
    * phpOpenArithmeticEvaluator 1.0
    * (c) by Mathias Krebs, mathias.krebs(at)gmail.com, 2008
    * license: LGPL
    *
    * Groups all functions related to the evaluation of the formulas.
    * 
    * Informal description
    * The grammar supports basic arithmetic expressions, using the operators
    * *,/,+ and - as well as parentheses and the floating point.
    * 
    * Grammar
    * <expr> -> <term> ('+' <term> | '-' <term>)<br>
    * <term> -> <factor> ('*' <factor> | '/' <factor>)<br>
    * <factor> -> <value> | '(' <expr> ')' | '-' <factor> | '+' <factor><br><br>
    * <value> -> <i>numeric value</i>
    */
    
   define("EXPRESSION", 0);
   define("IDENTIFIER", 1);

   /* ----------------------------------------------------- */
   /* Set of functions related to the error handling start. */

   /**
    * Resets the division by zero flag.
    */   
   function resetErrors()
   {
      global $divProblem,$syntaxProblem;
      $divProblem    = 0;
      $syntaxProblem = 0;
   }
   
   /**
    * Sets the division by zero flag.
    */ 
   function divideByZero()
   {
      global $divProblem;
      $divProblem = 1;
   }

   /**
    * Encapsulates the access to the syntax error flag.
    * @return  boolean  True, if a division by zero has occured since the last reset.
    */ 
   function reportError()
   {
      global $syntaxProblem;
      $syntaxProblem = 1;
   }
   
   /* --------------------------------------------------- */
   /* Set of functions related to the error handling end. */
   

   /* Set of functions for parsing and tokenizing start. */
   /* -------------------------------------------------- */
   
   /**
    * Replaces the identifiers whitin a correct formula by
    * the values given for a situation. The result of the expression
    * can then be determined by evaluate().
    * @param   String   The expression to replace the identifiers.
    * @param   Array    Contains the results accessible by identifier name.
    * @return  String   The expression, where the identifiers are replaced by the actual values.
    */
   function replaceIdents($aString,$inVars)
   {
      global $constants,$expr,$eVars;
      
      $expr = $aString;
      $parseState = EXPRESSION;

      while (checkForToken())
      {
         $aChar = getNextChar();
         
         switch($parseState)
         {
            case EXPRESSION:
               switch ($aChar)
               {
                  case '+': case '-': case '*': case '/': case '(': case ')': case ".":
                  case '>': case '<': case '=': case '!': case '&': case '|': case '^':
                  case '0': case '1': case '2': case '3': case '4':
                  case '5': case '6': case '7': case '8': case '9':
                     $aBuffer = $aBuffer . $aChar;
                  break;
                  default:
                     $bBuffer = $aChar;
                     $parseState = IDENTIFIER;
                  break;
               }
            break;
            case IDENTIFIER:
               switch ($aChar)
               {
                  case '+': case '-': case '*': case '/': case '(': case ')': case '^':
                  case '>': case '<': case '=': case '!': case '&': case '|':
                     if (isset($inVars[$eVars[$bBuffer]]))
                     {
                        $aBuffer = $aBuffer . $inVars[$eVars[$bBuffer]] . $aChar;
                     }
                     elseif (isset($constants[$eVars[$bBuffer]]))
                     {
                        $aBuffer = $aBuffer . $constants[$eVars[$bBuffer]] . $aChar;
                     }
                     else
                     {
                        $aBuffer = $aBuffer . "0" . $aChar;
                     }
                     $parseState = EXPRESSION;
                  break;
                  default:
                     $bBuffer = $bBuffer . $aChar;
                  break;
               }
            break;
         }
      }
      
      if ($parseState == IDENTIFIER)
      {
         if (isset($inVars[$eVars[$bBuffer]]))
         {
            $aBuffer = $aBuffer . $inVars[$eVars[$bBuffer]];
         }
         elseif (isset($inVars[$eVars[$bBuffer]]))
         {
            $aBuffer = $aBuffer . $constants[$eVars[$bBuffer]];
         }
         else
         {
            $aBuffer = $aBuffer . "0";
         }
      }

      return $aBuffer;
   }
   
   /**
    * Removes line brakes and carriage returns and new line symbols from a string.
    * @param   String   The string to work on.
    * @return  String   The updated string.
    */
   function eliminateDecoration($aString)
   {  
      for ($i=0;$i<strlen($aString);$i++)
      {  
         if (ord($aString[$i]) != 32 && ord($aString[$i]) != 10 && ord($aString[$i]) != 13)
         {
            $bString .= $aString[$i];
         }
      }
      return $bString;
   }

   /**
    * Return the next character from the global string $expr. The character is removed
    * from $exprs.
    * @return Char   The first character of $expr.
    */
   function getNextChar()
   {
      global $expr;
      $aChar = $expr[0];
      eatSomeChars(1);
      return $aChar;   
   }
   
   /**
    * Determine if the token is numeric.
    * @param   String   The token to verify.
    * @return  boolean  True if the token is numeric, false otherwise.
    */
   function isNumber($aToken)
   {
      return is_numeric($aToken);
   }
   
   /**
    * Eat a given number of characters from the parsing expression.
    * @param   int   The number of characters to eat (take away).
    */
   function eatSomeChars($aNumber)
   {
      global $expr;
      $expr = substr($expr,$aNumber,strlen($expr));
   }
   
   /**
    * Check there are still some tokens at the parsing expression.
    * @return  boolean  True if there are some tokens left, false otherwise.
    */
   function checkForToken()
   {
      global $expr;
      $empty = false;
      if(strlen($expr) > 0)
      {
         $empty = true;
      }
      return $empty;
   }

   /**
    * Put a token back at the beginning of the parsing expression.
    * @param   String   The token to put back.
    */   
   function putBackToken($aToken)
   {
      global $expr;
      $expr = $aToken . $expr;
   }
   
   /**
    * Eat a series of numbers at the beginning of the parsing expression.
    * @return A series of numbers.
    */
   function eatSomeNumbers()
   {
      global $expr;

      $i = 0;
      $number = "";
      while (ord($expr[$i])>47 && ord($expr[$i])<58)
      {
         $number = $number . $expr[$i];
         $i++;
      }
      eatSomeChars($i);
      return $number;
   }

   /**
    * Basic parsing function, getting the next token. The token is taken from
    * the global string $expr.
    * @return  String   The next token.
    */
   function getNextToken()
   {
      global $expr;
      $aSymbol = $expr[0];
      
      if (checkForToken() == false)
      {
         reportError();
         $aToken = 1;
      }
      else
      {
         switch($aSymbol)
         {
            case "+": case "-": case "*":
            case "/": case "(": case ")": case "^":
            case "=": case "&": case "|": case "!":
               eatSomeChars(1);
               $aToken = $aSymbol;
            break;

            case ">": case "<":
               if ($expr[1] == "=")
               {
                  $aToken = $expr[0] . $expr[1];
                  eatSomeChars(2);
               }
               else
               {
                  eatSomeChars(1);
                  $aToken = $aSymbol;
               }
            break;
            
            case "1": case "2": case "3": case "4": case "5":
            case "6": case "7": case "8": case "9": case "0":
               $number = $number . eatSomeNumbers();
               $aDot = getNextChar();
               if ($aDot == ".")
               {
                  $number = $number . $aDot;
                  $number = $number . eatSomeNumbers();
                  $aDot = getNextChar();
                  if ($aDot == 'e')
                  {
                     $number = $number . $aDot;
                     $number = $number . eatSomeNumbers();    
                  }
                  elseif ($aDot == 'E')
                  {
                     $number = $number . $aDot . getNextChar();
                     $number = $number . eatSomeNumbers();
                  }
                  else
                  {
                     putBackToken($aDot);
                  }
               }
               elseif ($aDot == 'e')
               {
                  $number = $number . $aDot;
                  $number = $number . eatSomeNumbers();    
               }
               elseif ($aDot == 'E')
               {
                  $number = $number . $aDot . getNextChar();
                  $number = $number . eatSomeNumbers();
               }
               else
               {
                  putBackToken($aDot);
               }
               $aToken = $number;
            break;
            
            default:
               reportError();
               eatSomeChars(1);
            break;
         }
      }
      
      return $aToken;
   }
   
   /* ------------------------------------------------ */
   /* Set of functions for parsing and tokenizing end. */
   
   
   /* Set of functions for the evaluation of arithmetic expressions start. */
   /* -------------------------------------------------------------------- */
   
   /**
    * Evaluates a given arithmetic expression. For the sake of simplicity, the expression is
    * stored in the global variable $expr.
    * @param   String   An arithetic expression to evaluate.
    * @return  int      The value of the evaluated expression.
    */
   function evaluate($aExpr)
   {
      global $expr, $divProblem, $syntaxProblem;
      
      $expr = $aExpr;
      $result = eatExpr();
      
      $error = "";
      if ($expr != "" || $syntaxProblem == 1)
      {
         $error = "Syntax error.";
		 resetErrors();
      }
      else if ($divProblem == 1)
      {
         $error = "Division by zero.";
		 resetErrors();
      }
      
      $back[0] = $result;
      $back[1] = $error;
      return $back;
   }
   
   /**
    * Parses and evaluates expressions.<br>
    * <expr> -> <term> ('+' <term> | '-' <term>).
    * @return int    Numeric value of the expression.
    */
   function eatExpr()
   {
      $aVal = eatTerm();
      
      while (checkForToken())
      {
         $aToken = getNextToken();
         if ($aToken == "+")
         {
            $bVal = eatTerm();
            $aVal = $aVal + $bVal;
         }
         elseif($aToken == "-")
         {
            $bVal = eatTerm();
            $aVal = $aVal - $bVal;
         }
         else
         {
            putBackToken($aToken);
            break;
         }
      }
      
      return $aVal;
   }

   /**
    * Parses and evaluates terms.<br>
    * <term> -> <factor> ('*' <factor> | '/' <factor>).
    * @return int    Numeric value of the term.
    */
   function eatTerm()
   {
      $aVal = eatFactor();

      while (checkForToken())
      {
         $aToken = getNextToken();
         
         if ($aToken == '*')
         {
            $bVal = eatFactor();
            $aVal = $aVal * $bVal;
         }
		 elseif ($aToken == '^')
         {
            $bVal = eatFactor();
            $aVal = pow($aVal, $bVal);
         }
         elseif($aToken == "/")
         {
            $bVal = eatFactor();
            if ($bVal != 0)
            {
               $aVal = $aVal / $bVal;
            }
            else
            {
               divideByZero();
               $aVal = 0;
            }
         }
         else
         {
            putBackToken($aToken);
            break;
         }
      }
      return $aVal;
   }
   
   /**
    * Parses and evaluates factors. <br>
    * <factor> -> <value> | '(' <expr> ')' | '-' <factor> | '+' <factor>.
    * @return int    Numeric value of the factor.
    */   
   function eatFactor()
   {
      $aToken = getNextToken();
      
      if ($aToken == '(')
      {
         $aVal = eatExpr();
         $aToken = getNextToken();
         if ($aToken != ")")
         {
            reportError();
         }
      }
      elseif ($aToken == '-')
      {
         $aVal = eatFactor() * (-1);
      }
      elseif ($aToken == '+')
      {
         $aVal = eatFactor();
      }
      else
      {
         if (isNumber($aToken))
         {
            $aVal = $aToken;
         }
         else
         {
            reportError();
         }
      }
      
      return $aVal;
   
   }
   
   /* ------------------------------------------------------------------ */   
   /* Set of functions for the evaluation of arithmetic expressions end. */
?>
