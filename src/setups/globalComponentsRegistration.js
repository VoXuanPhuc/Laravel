// Base Components
import EcButton from "@/components/EcButton"
import EcCheckbox from "@/components/EcCheckbox"
import EcCheckboxGroup from "@/components/EcCheckboxGroup"
import EcDatePicker from "@/components/EcDatePicker"
import EcHtmlRenderer from "@/components/EcHtmlRenderer"
import EcInputDateOfBirth from "@/components/EcInputDateOfBirth"
import EcInputLongText from "@/components/EcInputLongText"
import EcInputNumber from "@/components/EcInputNumber"
import EcInputNumberStepper from "@/components/EcInputNumberStepper"
import EcInputText from "@/components/EcInputText"
import EcMessage from "@/components/EcMessage"
import EcRadios from "@/components/EcRadios"
import EcSelect from "@/components/EcSelect"
import EcSpinner from "@/components/EcSpinner"
import EcSlider from "@/components/EcSlider"
import EcToggle from "@/components/EcToggle"
import EcModalSimple from "@/components/EcModalSimple"
import EcMultiSelect from "@/components/EcMultiSelect"
import EcCalendar from "@/components/EcCalendar"
import EcBox from "@/components/EcBox"
import EcFlex from "@/components/EcFlex"
import EcText from "@/components/EcText"
import EcHeadline from "@/components/EcHeadline"
import EcLabel from "@/components/EcLabel"
import EcIcon from "@/components/EcIcon"

// Project components
import RMessage from "@/readybc/components/RMessage"
import RFormInput from "@/readybc/components/RFormInput"

// Base components
const baseComponents = {
  EcButton,
  EcCheckbox,
  EcCheckboxGroup,
  EcHtmlRenderer,
  EcInputDateOfBirth,
  EcInputLongText,
  EcInputNumber,
  EcInputNumberStepper,
  EcInputText,
  EcMessage,
  EcRadios,
  EcSelect,
  EcSpinner,
  EcSlider,
  EcToggle,
  EcModalSimple,
  EcCalendar,
  EcDatePicker,
  EcMultiSelect,
  EcBox,
  EcFlex,
  EcText,
  EcHeadline,
  EcLabel,
  EcIcon,
}

const coreComponents = {
  RFormInput,
  RMessage,
}

// Inject variant resolver function into base components
Object.keys(baseComponents).forEach((fileName) => {
  const component = baseComponents[fileName]
  const currentInject = component?.inject ?? []
  component.inject = [...currentInject, "getComponentVariants"] // this is provided on app level
})

// Register components
const components = { ...baseComponents, ...coreComponents }
export default (app) => {
  Object.keys(components).forEach((fileName) => {
    const componentConfig = components[fileName]
    app.component(fileName, componentConfig.default || componentConfig)
  })
}
