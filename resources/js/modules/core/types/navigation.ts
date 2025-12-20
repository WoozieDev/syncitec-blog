import type { Component } from 'vue'

export interface NavItem {
  label: string
  href: string
  pattern: RegExp

  // opcional: controlar visibilidad por permiso
  permission?: string

  // opcional: icono (componente Vue)
  icon?: Component
}
