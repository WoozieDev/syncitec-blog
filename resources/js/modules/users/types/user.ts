
export interface RoleSummary {
  id: number
  name: string
}

export interface UserListItem {
  id: number
  name: string
  email: string
  deleted_at: string | null
  roles: RoleSummary[]
}