export function makeGetComponentVariants({ variants }) {
  return ({ componentName, variant, variantsDefinition = undefined }) => {
    const component = variantsDefinition ? variantsDefinition?.[componentName] : variants?.[componentName]
    // If "default" points to another variant, look for that variant
    if (variant === "default") variant = component?.default
    return component?.[variant]
  }
}
