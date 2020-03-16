export function sortBudgetItems (items) {
  let results = []
  let grouped = _.sortBy(items, 'id').reverse()
  grouped = _.groupBy(grouped, 'year')

  _.forEach(grouped, function(value, key) {
    let first = true
    let childrens = []

    _.forEach(value, function(item) {
      if(!first) {
        childrens.push(item)
      }
      first = false
    })

    results.push(Object.assign(value[0], { children: childrens}))
  })

  return results
}
