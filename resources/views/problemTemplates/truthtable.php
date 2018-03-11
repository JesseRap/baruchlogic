<div class="formula">{{problem.prompt}}</div>
  <div class="truthTable">
    {% if problem.problem_type == 'truthtable' %}
      <script type="text/javascript">
        console.log("HELLO", {{problem.data}});
        // document.write(TruthTable.generateTableHTMLPartial('{{problem.data}}'));
      </script>
    {% endif %}
  </div>
