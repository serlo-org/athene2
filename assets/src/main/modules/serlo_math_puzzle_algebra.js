/**
 *
 * Interactive Mathematical Puzzles
 * Check validity of algebraic answers
 *
 * @author  Stefan Dirnstorfer
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
/* global define */

function computeValue (obj) {
  var use, value, args, i, sub

  // check for redirections
  use = obj.getAttribute('data-use')
  if (use) {
    obj = document.getElementById('def-' + use)
    if (obj.getAttribute('class') !== 'valid') return null
  }

  // The top:value attribute contains the formula
  value = obj.getAttribute('data-value')

  // recurse through child elements to find open arguments
  args = []
  for (i = 0; i < obj.childNodes.length; ++i) {
    if (obj.childNodes[i].nodeType === 1) {
      // if the child node has a value, compute it and
      // store in the argument list.
      sub = computeValue(obj.childNodes[i])
      if (sub) {
        args[args.length] = sub
      }
    }
  }

  // if value is a formula of child values
  if (value && value.indexOf('#') >= 0) {
    // replace #n substrings with appropriate sub values
    for (i = 0; i < args.length; ++i) {
      value = value.replace('#' + (i + 1), args[i])
    }
  } else {
    // By default return the one input argument
    if (args.length === 1) value = '(' + args[0] + ')'
  }
  return value
}

// verify whether the new object satisfies the winning test
function verify (svg) {
  // extract the user created formula in json
  var goal,
    obj = svg.querySelector('[data-goal]'),
    value = computeValue(obj)

  if (!value || value.indexOf('#') >= 0) {
    // break if formula is incomplete
    smile(svg, false)
  } else {
    // construct the objective function
    goal = obj.getAttribute('data-goal')
    smile(svg, isEquivalent(value, goal))
  }
}

// compare two alebraic expressions
function isEquivalent (value, goal) {
  // check for free variables
  var tries,
    context,
    value1,
    value2,
    i,
    j,
    vars = (goal + value).match(/\$[a-zA-Z][a-z0-9.]*/g) || []

  try {
    tries = 1 + 10 * vars.length
    for (i = 0; i < tries; ++i) {
      context = {}
      for (j = 0; j < vars.length; ++j) {
        if (!vars[j].match(/\./)) context[vars[j]] = Math.random() * 6 - 3
      }
      value1 = eval(value.replace(/\$/g, 'context.'))
      value2 = eval(goal.replace(/\$/g, 'context.'))
      if (isNaN(value1) !== isNaN(value2)) return false
      if (!isNaN(value1) && Math.abs(value1 - value2) > 1e-10) return false
    }
    return true
  } catch (e) {
    return false
  }
}

// sets the oppacitiy to show either of the two similies
function smile (svg, win) {
  var oldstyle = svg.parentNode.getAttribute('class'),
    newstyle = oldstyle.replace(/ solved/, '')
  if (win) newstyle = newstyle + ' solved'
  svg.parentNode.setAttribute('class', newstyle)
}

const Algebra = {
  verify: verify
}

export default Algebra
