/* eslint brace-style: [2, '1tbs', {'allowSingleLine': true}] */

/**
 * Constructor for natural deduction tables and checking moves
 */

function NaturalDeduction(containerDiv = '.naturalDeduction__table') {

  this.linesOfProof = [];
  this.containerDiv = containerDiv;

  /**
   * Factory function for making lines of the proof
   * @param {string} formula The formula for the line
   * @param {string} justification The justification for the line
   * @param {string} citedLines The justification for the line
   * @return {LineOfProof} Returns a LineOfProof object
   */
  this.LineOfProof = function(formula, justification, citedLines=[]) {
    console.log('THIS', this);
    this.formula = Formula.sanitizeFormula(formula);
    this.justification = justification;
    this.citedLines = citedLines;
    let temp = Formula.getMainOperatorAndOperands(formula);
    this.operator = temp.operator;
    this.operands = temp.operands.map(Formula.sanitizeFormula);
    this.isExercisePremise = false;
  }


  /**
   * Adds a new lineOfProof to the lines of proof and render the table
   * @param {LineOfProof} lineOfProof
   * @return {void}
   */
  this.addNewLine = function(lineOfProof) {
    // Add new line to the linesOfProof
    console.log("LINE@@ ", lineOfProof, this.linesOfProof)
    lineOfProof.isJustified = this.checkValidMove(lineOfProof);
    this.linesOfProof.push(lineOfProof);
    console.log("!!", this.linesOfProof);
    this.renderTable();
  }

  /**
   * The actions to perform when the submit button is pressed
   * Adds a new line to the proof table by drawing values from input fields
   * @return {void}
   */
  // const that = this;
  this.onSubmit = function() {
    // Make new LineOfProof and add to this.linesOfProof
    // array of the input fields
    console.log("HEY", this.containerDiv);
    let newLine = Array.from($(this.containerDiv + ' .js-naturalDeduction__input'));
    // mapped to their values:
    newLine = newLine.map( (el) => {return el.value;});
    // split the cited lines into an array
    newLine[2] = newLine[2].split(/[\s,]*/);
    // make new LineOfProof
    const newLineOfProof = new this.LineOfProof(...newLine);
    console.log(newLine);
    const check = this.checkNumberOfCitedLines(newLine[1], newLine[2]);
    if (check) {
      console.log("????", this);
      this.addNewLine(newLineOfProof);
    } else {
      alert(`Something's wrong with your line citations`);
    }
  }


  /**
   * Checks whether the number of cited lines is appropriate for the cited rule
   * @param {string} rule The cited rule
   * @param {array} citedLines An array of cited lines
   * @return {boolean} Is the number of cited lines appropriate to the
   *    cited rule of justification?
   */
  this.checkNumberOfCitedLines = function(rule, citedLines) {
    // Ensure that the appropriate number of lines have been cited.
    const map = {
      'Premise': 0,
      'Addition': 1,
      'Conjunction': 2,
      'Contrapositive': 1,
      'Double on': 1,
      'Disjunctive Syllogism': 2,
      'Modus Ponens': 2,
      'Simplification': 1,
    };
    return citedLines.length === map[rule];
  }

  /**
   * Render the deduction table to the container
   * @param {string} tableContainerSelector @default '.naturalDeduction--table'
   *    The container where the HTML will be rendered
   */
   this.renderTable = function() {
    console.log("RENDER TABLE");
    // Render the HTML for the natural deduction table
    console.log('render ', this.linesOfProof, this.containerDiv);
    const container = $(this.containerDiv + ' table')[0];
    console.log('RENDER ', container);
    html = `<tr class='naturalDeduction__table--header'>
              <th>Line Number</th>
              <th>Proposition</th>
              <th>Justification</th>
              <th>Cited Lines</th>
            </tr>`;
    for (let i = 0; i < this.linesOfProof.length; i++) {
      const line = this.linesOfProof[i];
      console.log(i, line);
      if (i < this.linesOfProof.length - 1) {
        html += renderRow(line, i);
      } else {
        console.log("LAST LINE");
        html += renderRow(line, i, true);
      }
    }
    container.innerHTML = html;
  }

  /**
   * Generate the HTML for one row of the natural deduction table
   * @param {LineOfProof} line The line to generate the row
   * @param {int} i The index of the row
   * @return {string} The HTML for one row of the deduction table
   */
  function renderRow(line, i, showDeleteButton = false) {
    // Render the HTML for one row of the natural deduction table
    return `<tr class="naturalDeduction__row">
              <td>${i+1}</td>
              <td>${line.formula}</td>
              <td>${line.justification}</td>
              <td>${line.citedLines || ''}</td>
              ${showDeleteButton && !line.isExercisePremise ?
                  `<td class="removeRowButton">
                    <span class="x-span">X<span>
                  </td>` :
                  `<td class="removeRowButton--disabled">
                     <span class="x-span--disabled"><span>
                  </td>`
                 }
            </tr>`;
  }


  /**
   * Remove a row from the table, and update the other rows that cite that row
   * @param {int} rowNumber The index of the row to be removed
   */
  this.removeRowFromTable = function(rowNumber) {
    // Remove row from the table then adjust other rows that cite it
    this.linesOfProof.splice(rowNumber, 1);
    this.linesOfProof.forEach( (obj, idx) => {
      // Adjust the cited lines for other lines
      obj.citedLines = obj.citedLines.map( (line) => {
        if (line > rowNumber + 1) {
          // if the cited line is greater than the removed line, decrement
          return line - 1;
        } else if (line == rowNumber + 1) {
          // if the cited line is removed, ???
          return '???';
        } else {
          return line;
        }
      });
    });

    this.linesOfProof.forEach( (obj) => {
      // if a line has its cited line removed, it is no longer justified
      obj.isJustified = this.checkValidMove(obj);
    });

    console.log('WHAT ', this.linesOfProof);

    this.renderTable();
  }

  this.removeLastRow = function() {
    this.linesOfProof.pop();
    this.renderTable();
  }

  // function checkMainOperator(rule, operator) {
  //   // Check that the main operator matches the cited rule
  //   console.log('checkMainOperator', rule, operator);
  //   const opsForRules = {
  //     'Simplification': ['&', 'and'],
  //     'Addition': [],
  //     'Double Negation': [],
  //     'Modus Ponens': [],
  //     'Disjunctive Syllogism': ['V', 'or'],
  //     'Contrapositive': ['->', 'then'],
  //     'Conjunction': []
  //   }
  //   if (opsForRules[rule]) {
  //     return opsForRules[rule] &&
  //             (opsForRules[rule].length === 0 ||
  //               opsForRules[rule].indexOf(operator) >= 0);
  //   } else {
  //     return false;
  //   }
  // }


  /**
   * Check whether a line of the proof is a valid move
   * @param {LineOfProof} lineOfProof the line of proof to be checked
   * @return {boolena} Is the line of the proof valid/justified?
   */
  this.checkValidMove = function(lineOfProof) {
    // Check that the line of the proof is justified

    // ALLOW TOGGLE FOR 'LOOSE' OR 'STRICT' APPLICATION OF RULES

    console.log('checkValidMove ', lineOfProof);

    if (lineOfProof.justification.match(/premise/ig)) {
      // Premises are automatically justified
      return true;
    };
    console.log('???', lineOfProof.citedLines);
    if (lineOfProof.citedLines.indexOf('???') >= 0 ||
          lineOfProof.citedLines.some( (obj) => {
            // see if any of the cited lines are not justified
            console.log(obj-1, this.linesOfProof[obj-1]);
            return this.linesOfProof[obj-1].isJustified === false;
          })) {
      // cited line has been removed
      lineOfProof.isJustified = false;
      return false;
    };

      let result = false;

      // Go through both cited lines, and see if the main operator matches
      // the rule.
      lineOfProof.citedLines.forEach( (citedLineNum, idx) => {
        const rule = lineOfProof.justification;
        const lineNumber = citedLineNum - 1; // cited lines are 1-indexed
        let otherLineNumber;
        let otherCitedLine;
        const citedLine = this.linesOfProof[lineNumber];
        if (lineOfProof.citedLines.length > 1) { // two lines are cited
          otherLineNumber = lineOfProof.citedLines[(idx + 1)%2] - 1;
          otherCitedLine = this.linesOfProof[otherLineNumber];
        }

        console.log(rule, lineNumber, citedLine);

        // let operatorMatchesRules =
        //    checkMainOperator(rule, citedLine.operator);
        // if (!operatorMatchesRules) {
        //   console.log('OPERATOR DOESNT MATCH');
        //   return; // go to the next item in the loop
        // }


        switch (rule) {

          case 'Simplification': // Simplificaiton
            // See if the line is one of the operands of the cited line
            result = citedLine.operands.indexOf(lineOfProof.formula) >= 0 ?
                                                                true : result;
            break;

          case 'Double Negation': // Double Negation
            let {operator: mainOp2, operands: operands2} =
              this.getMainOperatorAndOperands(operands[0]);
            if (mainOp2 === '~') {
              result = lineOfProof.formula == operands2[0] ? true : result;
            }
            break;

          case 'Addition': // Addition
            console.log('A ', lineOfProof.operands, citedLine.formula);
            // The lineOfProof has to have the cited line as an operand
            result = lineOfProof.operands.indexOf(citedLine.formula) >= 0 ?
                                                              true : result;
            break;

          case 'Modus Ponens': // Modus Ponens
            console.log('MP ', citedLine, otherCitedLine);
            if (citedLine.operands[0] === otherCitedLine.formula) {
              if (citedLine.operands[1] === lineOfProof.formula) {
                result = true;
              }
            }
            break;

          case 'Disjunctive Syllogism': // Disjunctive Syllogism
            console.log('DS ', citedLine, otherCitedLine);
            if (otherCitedLine.operator === '~' ||
                otherCitedLine.operator === 'not') {
              result = citedLine.operands
                        .indexOf(otherCitedLine.operands[0]) ? true : result;
            }
            break;

          case 'Contrapositive': // Contrapositive
            console.log('CONTRAPOSITIVE', citedLine, lineOfProof.formula);
            if (citedLine.operator === '->') {
              // The operands of the current line should be the negation of
              // the operands of the cited line, in reverse order
              result = citedLine.operands.slice().reverse().every(
                  (obj, idx) => {
                return aIsNegationOfB(new this.LineOfProof(lineOfProof.operands[idx]),
                                      new this.LineOfProof(obj));
              }) ? true : result;
            }
            break;

          case 'Conjunction': // Conjunction
            // The cited lines should appear in the operands of the formula
            console.log('CONJUNCTION', lineOfProof.operands,
                        citedLine.formula, otherCitedLine.formula);
            if (lineOfProof.operands.indexOf(citedLine.formula) >= 0) {
              if (lineOfProof.operands.indexOf(otherCitedLine.formula) >= 0) {
                result = true;
              }
            }

        }
      });
      console.log('returning ', result);
      return result;
  }

  /**
   * Check whether two arrays have the same elements in the same order
   * @param {array} arr1 The first input array
   * @param {array} arr2 The second input array
   * @return {boolean} Are the arrays "equal"?
   */
  function checkArraysEqual(arr1, arr2) {
    // Check arrays contain same elements in same order
    let i = 0;
    if (arr1.length !== arr2.length) return false;
    while (i < arr1.length) {
      if (arr1[i] !== arr2[i]) {
        return false;
      }
      i++;
    }
    return true;
  }

  /**
   * Determine whether the first argument is the negation of the second arg
   * @param {LineOfProof} a The first argument
   * @param {LineOfProof} b The second argument
   * @return {boolean} Is the first argument the negation of the second arg?
   */
  function aIsNegationOfB(a, b) {
    return a.operator === '~' && a.operands[0] === b.formula;
  }

  /**
   * Computes whether either input line is the negation of the other
   * @param {LineOfProof} a One of the lines to be compared
   * @param {LineOfProof} b One of the lines to be compared
   * @return {boolean} Is either input line the negation of the other?
   */
  function oneIsNegationOfOther(a, b) {
    return aIsNegationOfB(a, b) || aIsNegationOfB(b, a);
  }

  // function aIsLooseNegationOfB(a, b) {
  //   return [a, b].some( (obj, idx) => {
  //     const otherLine = [a, b].filter( (o) => {return o !== obj;});
  //     console.log(obj, otherLine);
  //   });
  // }



  /**
   * Preload a set of premises into the deduction table
   */
  this.addPremises = function(premises) {
    console.log("ADD PREMISES");
    premises.forEach( (obj, idx) => {
      const newLine = new this.LineOfProof(obj, 'Premise', []);
      newLine.isExercisePremise = true;
      this.addNewLine(newLine);
    });
  }

  // return {
  //   onSubmit,
  //   removeRowFromTable,
  //   linesOfProof: this.linesOfProof,
  //   getMainOperatorAndOperands,
  //   checkValidMove,
  //   aIsNegationOfB,
  //   checkArraysEqual,
  //   LineOfProof,
  //   oneIsNegationOfOther,
  //   addNewLine,
  //   addPremises,
  // };
}

// $('#naturalDeduction--submitButton').click(NaturalDeduction.onSubmit);


// $(window).click( (event)=> {
//   console.log(event.target);
//   if (event.target.classList.contains('x-span')) {
//     console.log('HOORAY');
//     const thisSpanIdx = $('.x-span').index(event.target);
//     console.log('idx ', thisSpanIdx);
//     NaturalDeduction.removeRowFromTable(thisSpanIdx);
//   };
// });
