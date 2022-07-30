export function constructQuery({ defaultFragment, defaultQuery, fragment, queryOverride }) {
  // If there is queryOverride, it is a total override of the query and up to consumer to put it toghether, this is an edge case
  // Otherwise pass fragment if provided by the consumer or use defaultFragment

  // Produces query like:
  /*
    # Fragment is rendered here
    fragment cases on cases {
      totalCount
      list {
        name
        createdAt
        lastModifiedAt
      }
    }

    # Default query here
        query cases($limit: Int, $sort: sortInput, $skip: Int, $where: caseWhere, $asOf: DateTimeOffset) {
            cases(limit: $limit, sort: $sort, where: $where, skip: $skip, asOf: $asOf) {
                ...cases
            }
        }
  */

  return queryOverride || `${fragment || defaultFragment} ${defaultQuery}`
}
