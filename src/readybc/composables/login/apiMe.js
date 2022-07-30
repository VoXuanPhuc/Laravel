import { gql } from "../api/gql"
import { validateParams } from "../api/validateParams"
import { constructQuery } from "../api/constructQuery"
import { resolveFetcherErrors } from "../api/resolveFetcherErrors"
import { resolveAuthQueryMutationErrors } from "../api/resolveAuthQueryMutationErrors"

export function apiMe(params = {}) {
  validateParams(params)
  const { variables, fragment, fetcher, queryOverride, locale, token } = params

  const defaultQuery = gql`
    query me {
      me {
        ...result
      }
    }
  `

  const defaultFragment = gql`
    fragment result on login {
      id
      permissionGroups {
        ...permissionGroup
      }
      associatedUser {
        ...entity
      }
      email
      username
      isEmailConfirmed
      createdAt
    }

    fragment Value on scalarValue {
      booleanValue
      dateValue
      stringValue
      numberValue
    }

    fragment TreeMapValues on scalarValue {
      ...Value
      arrayValue {
        ...Value
        objectValue {
          key
          value {
            ...Value
          }
        }
      }
      objectValue {
        key
        value {
          ...Value
        }
      }
    }

    fragment fact on fact {
      id
      type
      value {
        ...TreeMapValues
      }
    }

    fragment entity on entityInterface {
      id
      name
      facts {
        ...fact
      }
      source
    }

    fragment permissionGroup on permissionGroup {
      id
      name
      createdAt
      targettedPermissions {
        ...targettedPermission
      }
    }

    fragment targettedPermission on targettedPermission {
      permission {
        id
        name
        description
      }
      targetIds
    }
  `

  const query = constructQuery({
    defaultFragment,
    defaultQuery,
    fragment,
    queryOverride,
  })

  return new Promise((resolve) => {
    fetcher({ query, variables, locale, token })
      .then((response) =>
        resolve({
          data: response?.data?.me,
          response,
          error: resolveAuthQueryMutationErrors({
            type: "query",
            errors: response?.errors,
            dataToCheck: response?.data?.me,
          }),
        })
      )
      .catch((fetcherError) => resolve(resolveFetcherErrors(fetcherError)))
  })
}
