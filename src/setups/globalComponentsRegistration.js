// Base Components
import EcButton from "@/components/EcButton"
import EcCheckbox from "@/components/EcCheckbox"
import EcCheckboxGroup from "@/components/EcCheckboxGroup"
import EcDatePicker from "@/components/EcDatePicker"
import EcColorPicker from "@/components/EcColorPicker"
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
import EcTooltip from "@/components/EcTooltip"
import EcNetwork from "@/components/EcNetwork"
import EcCarousel from "@/components/EcCarousel"
import EcPdfViewer from "@/components/EcPdfViewer"
import EcImageViewer from "@/components/EcImageViewer"

// Project components
import RMessage from "@/modules/core/components/common/RMessage"
import RFormInput from "@/modules/core/components/common/RFormInput"
import RLayoutDefault from "@/modules/core/components/common/RLayoutDefault"
import RLayout from "@/modules/core/components/common/RLayout"
import RLayoutTwoCol from "@/modules/core/components/common/RLayoutTwoCol"
import RBreadcrumb from "@/modules/core/components/common/RBreadcrumb"
import RProgressBar from "@/modules/core/components/common/RProgressBar"
import RQuoteHeadline from "@/modules/core/components/common/RQuoteHeadline"
import RSidebar from "@/modules/core/components/common/RSidebar"
import RSidebarMenu from "@/modules/core/components/common/RSidebarMenu"
import RSidebarMenuItem from "@/modules/core/components/common/RSidebarMenuItem"
import RSidebarMobile from "@/modules/core/components/common/RSidebarMobile"
import RPagination from "@/modules/core/components/common/RPagination"
import RPaginationStatus from "@/modules/core/components/common/RPaginationStatus"
import RSearchBox from "@/modules/core/components/common/RSearchBox"
import RTabs from "@/modules/core/components/common/RTabs"
import REditableField from "@/modules/core/components/common/REditableField"
import RDatePickerAdvanced from "@/modules/core/components/common/RDatePickerAdvanced"
import RDroppableZone from "@/modules/core/components/common/RDroppableZone"
import RFileRow from "@/modules/core/components/common/RFileRow"
import RUploadFiles from "@/modules/core/components/common/RUploadFiles"
import RLoading from "@/modules/core/components/common/RLoading"
import RTopBar from "@/modules/core/components/common/RTopBar"
import RModalPreview from "@/modules/core/components/common/RModalPreview"
import RFileSlider from "@/modules/core/components/common/RFileSlider"
import RActivityLog from "@/modules/core/components/common/RActivityLog"
import RActivityLogRow from "@/modules/core/components/common/RActivityLogRow"
import RCommentRow from "@/modules/core/components/common/RCommentRow"

// Table
import RTable from "@/modules/core/components/RTable/RTable"
import RTableAction from "@/modules/core/components/RTable/RTableAction"
import RTableCell from "@/modules/core/components/RTable/RTableCell"
import RTableHeaderCell from "@/modules/core/components/RTable/RTableHeaderCell"
import RTableHeaderRow from "@/modules/core/components/RTable/RTableHeaderRow"
import RTableRow from "@/modules/core/components/RTable/RTableRow"

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
  EcColorPicker,
  EcMultiSelect,
  EcBox,
  EcFlex,
  EcText,
  EcHeadline,
  EcLabel,
  EcIcon,
  EcTooltip,
  EcNetwork,
  EcCarousel,
  EcPdfViewer,
  EcImageViewer,
}

const coreComponents = {
  RBreadcrumb,
  RFormInput,
  RLayoutDefault,
  RLayout,
  RLayoutTwoCol,
  RMessage,
  RProgressBar,
  RQuoteHeadline,
  RSidebar,
  RSidebarMenu,
  RSidebarMenuItem,
  RSidebarMobile,
  RPagination,
  RPaginationStatus,
  RSearchBox,
  RLoading,
  RTopBar,
  RModalPreview,
  RFileSlider,
  RActivityLog,
  RActivityLogRow,
  RCommentRow,

  RTable,
  RTableAction,
  RTableCell,
  RTableHeaderCell,
  RTableHeaderRow,
  RTableRow,
  RTabs,
  REditableField,
  RDatePickerAdvanced,
  RDroppableZone,
  RFileRow,
  RUploadFiles,
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
