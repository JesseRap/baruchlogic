function nextLetterInSpan(span) {
  console.log('nextLetterInSpan', span.innerHTML);
  if (span.innerHTML.indexOf('>') === -1) {
    console.log('NO SPAN');
    return '<span class="highlight">' + span.innerHTML[0] + '</span>' + span.innerHTML.slice(1);
  }
  if (span.innerHTML[span.innerHTML.length - 1] === '>') {
    let highlightedLetter = span.innerHTML[span.innerHTML.length - 8];
    return span.innerHTML.slice(0, span.innerHTML.length - 32) + highlightedLetter;
  }
  for (let idx = span.innerHTML.length - 1; idx > 0; idx--) {
    if (span.innerHTML[idx] === '>') {
      console.log('SPAN AT ', idx);
      let nextHighlight = span.innerHTML[idx + 1];
      let lastHighlight = span.innerHTML[idx - 7];
      return span.innerHTML.slice(0, idx - 31) + lastHighlight + '<span class="highlight">' + nextHighlight + '</span>' + span.innerHTML.slice(idx + 2);
    }
  }
}

function animateText(span = null) {
  if (span === null) {
    span = $('.home__text .centerText > span:not(.dummy)')[0];
  }
  console.log(span.innerHTML);
  span.innerHTML = nextLetterInSpan(span);
  console.log(span.innerHTML);
  if (span.innerHTML.indexOf('>') > -1) {
    console.log('STILL HAS SPAN', span.innerHTML.indexOf('>'));
    ((s)=> {
      setTimeout(animateText.bind(null, s), 25);
    })(span);
  }
  else {
    console.log('NO MORE SPAN');
    let nextSpanIdx = $('.home__text .centerText > span:not(.dummy)').index(span) + 1;
    nextSpanIdx = nextSpanIdx % $('.home__text .centerText > span:not(.dummy)').length;
    let nextSpan = $('.home__text .centerText > span:not(.dummy)')[nextSpanIdx];
    console.log(nextSpan);
    animateText(nextSpan);
  }
}

function nextSpan(span) {
  let spans = $('.home__text .centerText > span:not(.dummy)');
  let currentSpanIdx = spans.index(span);
  let nextSpanIdx = (currentSpanIdx + 1) % spans.length;
  console.log(currentSpanIdx, nextSpanIdx);
  return spans[nextSpanIdx];
}

function findHighlightedSpan() {
  let result = null;
  let spans = $('.home__text .centerText > span:not(.dummy)');
  spans.each( (idx, obj) => {
    console.log($(obj).html());
    if ($(obj).html().indexOf('>') > -1) {
      console.log('yes');
      result = obj;
      return false;
    }
  });
  return result;
}
