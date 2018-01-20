/**
 * Utility functions
 */

/**
 * Sort an array by length, then by alphabet for one-place arrays
 * @param {array} arr is the array to be sorted
 * @return {array} a sorted copy of the array
 */
function sortByLength(arr) {
  // Sort an array by length of elements, or by alphabet if atomic
  let result = arr.sort( (a, b) => {
    if (a.length === 1 && b.length === 1) {
      // if the propositions are atomic, sort by alphabet
      return a > b;
    } else { // sort by length ascending
      return a.length > b.length;
    }
  });
  return result;
}

/**
 * Remove duplicate elements from an array
 * @param {array} arr is the array to be de-duped
 * @return {array} a de-duped copy of the array
 */
function removeDupesFromArray(arr) {
  // remove duplicate elements from array by converting to set
  // and then converting back to array
  return Array.from(new Set(arr));
}
