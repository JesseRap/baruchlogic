<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
  public $proof;

/**
 * Determine whether the first argument is the negation of the second arg
 * @param {string} $encodedFormula The encoded formula string
 * Formulae are encoded as {justification}|{citedLines}|{formula}
 * @return {LineOfProof} The generated LineOfProof
 */
function buildLineOfProofFromEncodedFormula($encodedFormula)
{
  $result = (object) array();
  $temp = explode('|', $encodedFormula);

  $result->justification = $temp[0];
  $result->citedLines = explode(',', $temp[1]);
  $result->citedLines = array_filter($result->citedLines, function($el) {
    return strlen($el) > 0;
  });
  $result->formula = $temp[2];

  $result = new LineOfProof($result->formula,
                            $result->justification,
                            $result->citedLines);

  return $result;
}

/**
 * Determine whether the first argument is the negation of the second arg
 * @param {string} $encodedProof The encoded formula string
 * Proof are encoded as {encoded line 1}!{encoded line 2}!{encoded line 3}
 * @return {array} Array of LineOfProof lines that constitue the proof
 */
function buildProofFromEncodedProof($encodedProof)
{
  $proof = array();
  $lines = explode('!', $encodedProof);

  foreach ($lines as $line)
  {
    array_push($proof, $this->buildLineOfProofFromEncodedFormula($line));
  }
  $this->proof = $proof;
  return $proof;
}


/**
 * Check whether a line of the proof is properly justified
 * @param {LineOfProof} $lineOfProof The line to be checked
 * @param {array} $proof The entire proof
 * @return {boolean} Is the line justified
 */
function checkValidMove($lineOfProof, $proof)
{
  if ($lineOfProof->justification === 'Premise')
  { // Premises are automatically justified
    return TRUE;
  }

  // if (array_search('???', $lineOfProof->citedLines) !== FALSE ||
  //     array_any($lineOfProof->citedLines, function($x) use ($proof)
  //               {
  //                 return $proof[$x-1]->isJustified === FALSE;
  //               }))
  // {
  //   $lineOfProof->isJustified = FALSE;
  //   return FALSE;
  // }

  $result = FALSE;

  foreach ($lineOfProof->citedLines as $idx=>$citedLineNumber)
  {
    $citedLineIndex = $citedLineNumber - 1; // cited lines are 1-indexed
    $otherLineNumber;
    $otherCitedLine;
    $citedLine = $proof[$citedLineIndex];
    if (count($lineOfProof->citedLines) > 1)
    {
      $otherCitedLineIndex = $lineOfProof->citedLines[($idx+1)%2] - 1;
      $otherCitedLine = $proof[$otherCitedLineIndex];
    }

    switch ($lineOfProof->justification) {

      case 'Addition':
        // The lineOfProof has to have the cited line as an operand
        if ($lineOfProof->operator === 'V')
        {
          $result = array_search($citedLine->formula, $lineOfProof->operands)
            !== FALSE ? TRUE : $result;
        }
        break;


      case 'Associativity':
        $result =
          $lineOfProof->oneIsAssociationOfOther($lineOfProof, $citedLine) ?
            TRUE : $result;
        break;

      case 'Commutativity': // One line must be a commutation of the other
        $result =
          $lineOfProof->oneIsCommutationOfOther($lineOfProof, $citedLine) ?
            TRUE : $result;
        break;

      case 'Conjunction':
        // The cited lines must equal the lineOfProof's operands
        if ($lineOfProof->operator === '&')
        {
          $result =
            ($lineOfProof->operands[0] === $citedLine->formula &&
            $lineOfProof->operands[1] === $otherCitedLine->formula) ||
            ($lineOfProof->operands[1] === $citedLine->formula &&
            $lineOfProof->operands[0] === $otherCitedLine->formula) ? TRUE : $result;
        }
        break;


      case "De Morgan's Laws":
        if ($lineOfProof->operator === '~')
        {
          $line = new LineOfProof($lineOfProof->operands[0], '', []);
          if ($line->operator === '&' && $citedLine->operator === 'V')
          {
            $result = $line->oneIsNegationOfOther(new LineOfProof($line->operands[0], '', []), new LineOfProof($citedLine->operands[0], '', [])) &&
                    $line->oneIsNegationOfOther(new LineOfProof($line->operands[1], '', []), new LineOfProof($citedLine->operands[1], '', [])) ?
                    TRUE : $result;
          }
        }
        if ($lineOfProof->operator === 'V' && $citedLine->operator === '~')
        {
          $line = new LineOfProof($citedLine->operands[0], '', []);
          $operands = $line->operands;
          $result = $line->oneIsNegationOfOther(new LineOfProof($line->operands[0], '', []), new LineOfProof($lineOfProof->operands[0], '', [])) &&
                  $line->oneIsNegationOfOther(new LineOfProof($line->operands[1], '', []), new LineOfProof($lineOfProof->operands[1], '', [])) ?
                  TRUE : $result;

        }
        if ($lineOfProof->operator === '~')
        {
          $line = new LineOfProof($lineOfProof->operands[0], '', []);
          if ($line->operator === 'V' && $citedLine->operator === '&')
          {
            $result = $line->oneIsNegationOfOther(new LineOfProof($line->operands[0], '', []), new LineOfProof($citedLine->operands[0], '', [])) &&
                    $line->oneIsNegationOfOther(new LineOfProof($line->operands[1], '', []), new LineOfProof($citedLine->operands[1], '', [])) ?
                    TRUE : $result;
          }
        }
        if ($lineOfProof->operator === '&' && $citedLine->operator === '~')
        {
          $line = new LineOfProof($citedLine->operands[0], '', []);
          $result = $line->oneIsNegationOfOther(new LineOfProof($line->operands[0], '', []), new LineOfProof($lineOfProof->operands[0], '', [])) &&
                  $line->oneIsNegationOfOther(new LineOfProof($line->operands[1], '', []), new LineOfProof($lineOfProof->operands[1], '', [])) ?
                  TRUE : $result;

        }
        break;


      case 'Disjunctive Syllogism': // Disjunctive Syllogism
        if ($citedLine->operator === 'V')
        { // One of the citedLine's operands must be the negation of the
          // otherCitedLine, and the formula must be the other operand
          $negatedIdx = 0; // Keeps track of which of the operands is the negation
          $idx = -1; // Needed in order to serve as the loop index
          // (There's probably a cleaner way to do this.)
          if (array_any($citedLine->operands,
            function($el) use ($lineOfProof, $otherCitedLine, &$negatedIdx, &$idx)
              {
                $idx += 1;
                if ($lineOfProof->oneIsNegationOfOther(new LineOfProof($el, '', []), $otherCitedLine))
                {
                  $negatedIdx = $idx;
                  return TRUE;
                }
                else {
                  return FALSE;
                }
              }))
          {
            $result = $lineOfProof->formula === $citedLine->operands[($negatedIdx+1)%2] ? TRUE : $result;
          }
        }
        break;


      case 'Distribution':
        $result = $lineOfProof->oneIsDistributionOfOther($lineOfProof, $citedLine) ? TRUE : $result;
        break;


      case 'Double Negation': // Double Negation
        $result = $lineOfProof->oneIsDoubleNegationOfOther($lineOfProof, $citedLine) ? TRUE : $result;

        break;


      case 'Hypothetical Syllogism': // Hypothetical Syllogism
        if ($lineOfProof->operator === '->' &&
            $citedLine->operator === '->' &&
            $otherCitedLine->operator === '->' &&
            $citedLine->operands[1] === $otherCitedLine->operands[0])
        {
          $result = $lineOfProof->operands[0] === $citedLine->operands[0] &&
                    $lineOfProof->operands[1] === $otherCitedLine->operands[1] ?
                    TRUE : $result;
        }
        break;


      case 'Material Conditional':
        if (($lineOfProof->operator === '->' && $citedLine->operator === 'V') ||
            ($lineOfProof->operator === 'V' && $citedLine->operator === '->'))
        {
          $result = $lineOfProof->operands[1] === $citedLine->operands[1] &&
                $lineOfProof->oneIsNegationOfOther(new LineOfProof($lineOfProof->operands[0], '', []),
                                                  new LineOfProof($citedLine->operands[0], '', [])) ?
                                                  TRUE : $result;
        }

      case 'Modus Ponens': // Modus Ponens
        if ($citedLine->operator === '->')
        {
          if ($citedLine->operands[0] === $otherCitedLine->formula) {
            if ($citedLine->operands[1] === $lineOfProof->formula) {
              $result = TRUE;
            }
          }
        }
        break;


      case 'Modus Tollens': // Modus Ponens
        if ($citedLine->operator === '->')
        {
          if ($lineOfProof->oneIsNegationOfOther( // DOES NOT ENFORCE DOUBLE NEGATION STRICLY
              new LineOfProof($citedLine->operands[1], '', []), $otherCitedLine))
            {
            if ($lineOfProof->oneIsNegationOfOther(
              $lineOfProof, new LineOfProof($citedLine->operands[0], '', [])))
            {
              $result = TRUE;
            }
          }
        }
        break;


      case 'Simplification': // Simplificaiton
        // See if the line is one of the operands of the cited line
        if ($citedLine->operator === '&')
        {
          $result = array_search($lineOfProof->formula, $citedLine->operands)
            !== FALSE ? TRUE : $result;
        }
        break;


      case 'Tautology':
        $result = $lineOfProof->oneIsTautologyOfOther($lineOfProof, $citedLine) ? TRUE : $result;
        break;


      case 'Transposition':
        if ($lineOfProof->operator === '->' && $citedLine->operator === '->')
        {
          $result = $lineOfProof->oneIsNegationOfOther(
                                    makeLine($lineOfProof->operands[0]),
                                    makeLine($citedLine->operands[1])) &&
                    $lineOfProof->oneIsNegationOfOther(
                                    makeLine($lineOfProof->operands[1]),
                                    makeLine($citedLine->operands[0])) ? TRUE : $result;
        }
        break;
    }
  }
  $lineOfProof->isJustified = $result;
  return $result;
}

function checkValidProof($proof, $goal = '')
{
  if (strlen($goal) > 0)
  {
    $lastLine = $proof[count($proof)-1];
    if ($lastLine->formula != $goal) {
      return FALSE;
    }
  }
  return array_all($proof, function($el) use ($proof) {
    return $this->checkValidMove($el, $proof);
  });
}
}
