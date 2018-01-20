/**
 * Module for truth tables
 */

TruthTable = (function() {


  /**
   * Build an array of all the required columns for the truth table
   * This includes all the atomic propositions, plus each additional operator
   * @param {string} formula The formula used to compute the truth table
   * @param {array} columnsArrayInput An array to accumulate the columns of
   *    the truth table while recursing on the parts of the formula
   * @param {boolean} topConnectiveIsIfThen is needed in order to prepend 'if'
   *    and know that 'then' is the right connective
   * @return {array} An array containing all the necessary columns for a truth
   *    table, as computed from a given formula
   */
  function buildColumnsFromFormula(formula, columnsArrayInput,
                                   topConnectiveIsIfThen = false) {
    let columnsArray = columnsArrayInput || [];
    formula = Formula.sanitizeFormula(formula);

    // find the topmost operator by finding the first operator where the count
    // of open parentheses is 0
    let parensCount = 0;
    for (let i = 0; i < formula.length; i++) {
      if (formula.trim().length === 1) { // formula is atomic
        return [formula];
      };
      const char = formula[i];
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
          return buildColumnsFromFormula(formula.slice(2),
                                         columnsArrayInput, true);
        };

        if (/^not/i.test(formula.slice(i, i+3))
              && !topConnectiveIsIfThen) { // topmost operator is 'not'
          console.log('NOT found in ', formula);
          columnsArray.push(formula);
          const tail = formula.slice(i+3);
          console.log('rest of formula ', tail);
          columnsArray = columnsArray.concat(
                          buildColumnsFromFormula(tail, columnsArray));
          break;
        };

        let group;
        if (topConnectiveIsIfThen) { // the top connective must be 'if...then'
          group = /(^then )/i.exec(formula.slice(i));
        } else {
          group = /(^and )|(^or )|(^if )|(^iff )|(^implies )|(^& )|(^V )/i
            .exec(formula.slice(i));
        }
        if (group) {
          console.log(group[0] + ' found in ', formula);
          if (group[0] === 'then ') {
            // re-attach the 'if' that was cut off
            columnsArray.push('if ' + formula);
          } else {
            columnsArray.push(formula);
          }
          console.log(group);
          const wordLength = group[0].length;
          console.log(wordLength);
          const head = formula.slice(0, i);
          const tail = formula.slice(i + wordLength);
          console.log('head is ', head);
          console.log('tail is ', tail);
          columnsArray = columnsArray.concat(
            buildColumnsFromFormula(head, columnsArray));
          columnsArray = columnsArray.concat(
            buildColumnsFromFormula(tail, columnsArray));
          break;
        };
      }
    }

    // return the sorted array with duplicates removed
    console.log('RESULT! ', sortByLength(removeDupesFromArray(columnsArray)));
    return sortByLength(removeDupesFromArray(columnsArray));
  }



  /**
   * Generate the HTML for a complete truth table with all cells calculated
   * @param {string} formula The formula used to generate the truth table
   * @return {string} The HTML for the complete truth table
   */
  function generateTableHTML(formula) {
    // Generates the HTML for the complete truth table for a formula

    columns = buildColumnsFromFormula(formula);
    html = '';

    // calculate number of atomic variables
    console.log('COLUMNS', columns);
    const atomicColumns = columns.filter( (el) => el.length === 1);
    const numAtomicVariables = atomicColumns.length;
    console.log('numAtomicVariables is ', numAtomicVariables);
    const numOfRows = Math.pow(2, numAtomicVariables);

    const initialTruthValues = generateInitialTruthValues(numAtomicVariables);

    // let atomicIdx = 0; // this is probably unnecessary

    for (let i = 0; i < columns.length; i++) {
      const el = columns[i];
      let colHTML = '<div class="truthTableColumn">' +
                    '<div class="truthTableCell">' + el + '</div>';
      if (el.length === 1) {
        // formula is atomic - assign values from initialTruthValues
        initialTruthValues[i].forEach( (val, idx) => {
          colHTML += '<div class="truthTableCell">' + val + '</div>';
        });
      } else { // calculate the truth value for that cell
        for (let row = 0; row < numOfRows; row++) {
          const truthValues = {};
          atomicColumns.forEach( (obj, idx) => {
            truthValues[obj] = initialTruthValues[idx][row] === 'T';
          });
          const cellValue = Formula.calculate(el, truthValues);
          colHTML += '<div class="truthTableCell">' +
                      (cellValue ? 'T' : 'F') + '</div>';
        }
      }
      html += colHTML + '</div>';
    };

    return html;
  }

  /**
   * Generate HTML for a truth table based on the input formula,
   * but only fill in the inital truth values of the atomic propositions
   * @param {string} formula The formula to generate the truth table
   * @return {string} The complete HTML for the truth table
   */
  function generateTableHTMLPartial(formula) {
    // Generates the HTML for a truth table with only atomic values filled in
    columns = buildColumnsFromFormula(formula);
    html = '';

    // calculate number of atomic variables
    console.log('COLUMNS', columns);
    const atomicColumns = columns.filter( (el) => el.length === 1);
    const numAtomicVariables = atomicColumns.length;
    console.log('numAtomicVariables is ', numAtomicVariables);
    const numOfRows = Math.pow(2, numAtomicVariables);

    const initialTruthValues = generateInitialTruthValues(numAtomicVariables);

    let atomicIdx = 0;

    for (let i = 0; i < columns.length; i++) {
      const el = columns[i];
      let colHTML = '<div class="truthTableColumn">' +
                    '<div class="truthTableCell">' + el + '</div>';
      if (el.length === 1) {
        // formula is atomic - assign values from initialTruthValues
        initialTruthValues[atomicIdx++].forEach( (val, idx) => {
          colHTML += '<div class="truthTableCell cell">' + val + '</div>';
        });
      } else {
        // formula is complex
        for (let row = 0; row < numOfRows; row++) {
          colHTML += '<div class="truthTableCell cell js-response-cell" ' +
                      'data-answer=""></div>';
        }
      }
      html += colHTML + '</div>';
    };

    return html;
  }


  /**
   * Generate an multi-dimensional array of truth values for
   * each atomic proposition column in the truth table
   * @param {int} numOfPropositions is the number of distinct
   *  propositional variables in the formula
   * @return {array} containing the initial truth values for the truth table
   */
  function generateInitialTruthValues(numOfPropositions) {
    // Generate all permutations of atomic truth values
    // in orderly alphabetic fashion
    numOfRows = Math.pow(2, numOfPropositions);
    let result = [];
    for (let i = 0; i < numOfPropositions; i++) {
      let thisRow = [];
      for (let j = 0; j < numOfRows; j++) {
        if (j % (numOfRows / (Math.pow(2, i))) <
                (numOfRows / (2 * Math.pow(2, i)))) {
          thisRow.push('T');
        } else {
          thisRow.push('F');
        }
      }
      result.push(thisRow);
    }
    return result;
  }

  /**
   * Render the HTML for complete truth table from an input formula
   * @param {string} formula is the formula used to generate the truth table
   * @return {void} Writes the result directly to the truth table container
   */
  function render(formula) {
    const container = document.querySelector('#truthTable');
    container.innerHTML = generateTableHTML(formula);
  }


  return {
    buildColumnsFromFormula,
    generateTableHTMLPartial,
    generateTableHTML,
    render,
  }
}

)();
