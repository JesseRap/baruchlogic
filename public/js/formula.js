/**
 * Module for functions for parsing and analyzing logical formulae
 * Creates global Formula object
 */

console.log('Formula loaded');

Formula = (function Formula(formula = '') {
  // Formula contains functions for parsing logical formulas,
  // computing truth values, building truth tables, and building tableaux

  /**
   * Takes a formula and removes all unnecessary whitespace and
   * extra parentheses
   * @param {string} formula The formula to be sanitized
   * @return {string} A copy of the sanitized formula
   */
  function sanitizeFormula(formula) {
    // Remove any extra whitespace and surrounding parentheses
    return trimExtraParens(
            formula.replace(/\s\s+/g, ' ') // get rid of multiple whitespaces
                  .replace(/\(\s+/g, '(') // remove whitespace after '('
                  .replace(/\s+\)/g, ')') // remove whitespace before ')'
                  .trim()); // trim extra whitespace
  }


  /**
   * Remove unnecessary parentheses surrounding an entire formula
   * @param {string} formula is the formula to be trimmed
   * @return {string} a trimmed copy of the string
   */
  function trimExtraParens(formula) {
    // Get rid of extra parens enclosing the whole formula
    if (formula[0] === '(' && formula[formula.length-1] === ')') {
      // if the first character and last chars are parens,
      // go thru and see where the first one closes;
      // if it closes before the end, return formula;
      // else if it closes at the end, remove the enclosing parens
      let count = 0;
      for (let i = 0; i < formula.length; i++) {
        count += formula[i] === '(';
        count -= formula[i] === ')';
        if (count === 0 && i < formula.length - 1) {
          // the opening parens closes before the last character - do nothing
          return formula;
        } else if (count === 0 && i === formula.length - 1) {
          // end of formula - closing parens;
          // slice off the first and last chars;
          // recurse on the result in case there are more extra parens
          return trimExtraParens(formula.slice(1, -1));
        }
      }
    } else {
      // the formula does not start and end with parens - do nothing
      return formula;
    }
  }


  /**
   * Calculate the truth value of a formula given the truth values
   * of the atomic propositions
   * @param {string} formula The formula to be calculated
   * @param {obj} truthValues An object containing each atomic propositional
   *  letter mapped to a truth value
   * @return {boolean} Is the formula true with the assignment of truth values?
   */
  function calculate(formula, truthValues) {
    // Calculate the truth value of a formula from the truth vales of the
    // atomic propositions
    // Modeled after Dijkstra's algorithm
    console.log('CALCULATE ', formula, truthValues);

    /* eslint-disable brace-style */
    const operatorFunctions = {
      'and ': (a, b) => {return a && b;},
      '& ': (a, b) => {return a && b;},
      'or ': (a, b) => {return a || b;},
      'V ': (a, b) => {return a || b;},
      'then ': (a, b) => {return !a || b;},
      'implies ': (a, b) => {return !a || b;},
      'if ': (a, b) => {return a || !b;},
      'not ': (a) => {return !a;},
    };
    /* eslint-enable brace-style */


    // Walk through the formula - when you find an atomic proposition,
    // push its value onto the values stack;
    // when you find an operator, push it onto the operator stack;
    // when you hit a closing parens, pop the requisite number of values and
    // pop an operator and apply the operator to the values -
    // then push the result back onto values

    let operators = [];
    let values = [];
    for (let i = 0; i < formula.length; i++) {
      console.log(i);
      const char = formula[i];
      const regexString = '(^and )|(^or )|(^if )|(^iff )|(^implies )' +
                          '|(^then )|(^not )|(^& )|(^V )';
      const re = new RegExp(regexString, 'i');
      const group = re.exec(formula.slice(i));
      console.log(char, group, operators, values);
      if (group) { // push operator
        operators.push(group[0]);
        i += group[0].length - 1;
      } else if (char === ')') {
        // pop operator, evaluate with values, and push result
        const op = operators.pop();
        let value1;
        let value2;
        if (op === 'not ') {
          value1 = null;
          value2 = values.pop();
        } else {
          value1 = values.pop();
          value2 = values.pop();
        }
        values.push(operatorFunctions[op](value2, value1));
      } else if (char.match(/[a-zA-Z]/)) {
        console.log('atomic formula');
        values.push(truthValues[char]);
      }
    }
    // End of formula reached - should remain at most one operator and
    // at most two values on the stack; this is because we are not allowing
    // unnecessary parens around the whole formula
    console.log(operators, values);
    if (operators.length === 0) {
      // if the formula is just an atomic proposiiton
      return values.pop();
    } else { // One operator remains on the stack
      const lastOperator = operators.pop();
      console.log('HERE', values);
      let value1;
      let value2;
      if (lastOperator === 'not ') {
        value1 = null;
        value2 = values.pop();
      } else {
        value1 = values.pop();
        value2 = values.pop();
      }
      result = operatorFunctions[lastOperator](value2, value1);
      console.log(result);
      return result;
    };
  }








  /**
   * Check if all parentheses are properly paired & closed
   * @param {string} formula is the string to tested
   * @return {boolean} true if there are missing parentheses in the formula
   */
  function checkMissingParentheses(formula) { // not sure if this is useful
    const re1 = /(and )|(or )|(if )|(iff )|(implies )|(then )|(not )/ig;
    const re2 = /then/ig;
    let match1 = formula.match(re1) || [];
    let match2 = formula.match(re2) || [];
    console.log(match1, match2);
    // 'if..then' matches twice, so remove one for each 'then'
    numOfOperators = match1.length - match2.length;
    let parensMatch = formula.match(/\(/ig) || [];
    numOfParens = parensMatch.length || 0;
    return numOfOperators - numOfParens > 1;
  }

  /**
   * Input a formula, get the main operator and operands for that formula
   * @param {string} formula The input string to be analyzed
   * @return {array} [the main operator of the formula, [the main operands]]
   */
  function getMainOperatorAndOperands(formula) {
    // find the topmost operator by finding the first operator where the count
    // of open parentheses is 0
    console.log('getMainOperatorAndOperands ', formula);
    formula = trimExtraParens(formula);
    result = {operator: null, operands: []};
    let parensCount = 0;
    for (let i = 0; i < formula.length; i++) {
      if (formula.trim().length === 1) { // formula is atomic
        result.operands = [formula];
        result.operator = null;
        return result;
      };
      const char = formula[i];
      console.log('i = ', i, char);
      console.log(formula);
      parensCount += char === '(';
      parensCount -= char === ')';
      // keep moving through the formula until there are no more open parens
      if (parensCount === 0) {
        // check the topmost operator

        // remove leading 'if'
        if (/^if/i.test(formula.slice(i, i+2))) {
          // if the formula starts with 'if', we just slice it off
          // and keep track of that fact in the recursion;
          // basically, we treat 'then' as 'if...then' and ignore the 'if'
        };

        if (/^not/i.test(formula.slice(i, i+3))) { // topmost operator is 'not'
          console.log('NOT found in ', formula);
          result.operator = '~';
          result.operands = [formula.slice(i+3)];
        };

        let group;
        const reString = '(^and )|(^or )|(^if )|(^iff )|(^implies )' +
                          '|(^then )|(^& )|(^V )|(^~)|(^-> )';
        const re = new RegExp(reString, 'i');
        group = re.exec(formula.slice(i));
        if (group) {
          console.log(group[0] + ' found in ', formula);
          console.log(group);
          const wordLength = group[0].length;
          console.log(wordLength);
          const head = formula.slice(0, i);
          const tail = formula.slice(i+wordLength);
          console.log('head is ', head);
          console.log('tail is ', tail);
          result.operator = group[0].trim();
          result.operands = [head.trim(), tail.trim()].filter(
                                                        (obj) => {
                                                          return obj !== '';
                                                        });
        };
      }
    }
    console.log('RESULT ', result);
    return result;
  }


  return {
    trimExtraParens,
    calculate,
    checkMissingParentheses,
    getMainOperatorAndOperands,
    sanitizeFormula,
  };
})();
