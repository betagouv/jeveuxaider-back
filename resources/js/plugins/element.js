import Vue from 'vue'
import {
  Alert,
  Button,
  ButtonGroup,
  Form,
  FormItem,
  Input,
  Select,
  Col,
  Icon,
  Row,
  DatePicker,
  TimeSelect,
  TimePicker,
  Checkbox,
  CheckboxButton,
  CheckboxGroup,
  Switch,
  Option,
  OptionGroup,
  InputNumber,
  Radio,
  RadioGroup,
  RadioButton,
  Table,
  TableColumn,
  Loading,
  MessageBox,
  Tabs,
  TabPane,
  Upload,
  Menu,
  Aside,
  Container,
  MenuItem,
  Header,
  Dropdown,
  DropdownItem,
  DropdownMenu,
  Avatar,
  Message,
  Tag,
  Tooltip,
  Card,
  Divider,
  Pagination,
  Steps,
  Step,
  Badge,
  Dialog,
} from 'element-ui'
import lang from 'element-ui/lib/locale/lang/fr'
import locale from 'element-ui/lib/locale'
import './element-variables.scss'

locale.use(lang)

Vue.use(Alert)
Vue.use(Button)
Vue.use(Form)
Vue.use(FormItem)
Vue.use(Input)
Vue.use(Select)
Vue.use(Col)
Vue.use(Row)
Vue.use(Icon)
Vue.use(DatePicker)
Vue.use(Switch)
Vue.use(Input)
Vue.use(InputNumber)
Vue.use(Radio)
Vue.use(RadioGroup)
Vue.use(RadioButton)
Vue.use(Checkbox)
Vue.use(CheckboxButton)
Vue.use(CheckboxGroup)
Vue.use(Switch)
Vue.use(Select)
Vue.use(Option)
Vue.use(OptionGroup)
Vue.use(Button)
Vue.use(ButtonGroup)
Vue.use(Table)
Vue.use(TableColumn)
Vue.use(DatePicker)
Vue.use(TimeSelect)
Vue.use(TimePicker)
Vue.use(Tabs)
Vue.use(TabPane)
Vue.use(Upload)
Vue.use(Menu)
Vue.use(Aside)
Vue.use(Container)
Vue.use(MenuItem)
Vue.use(Header)
Vue.use(Dropdown)
Vue.use(DropdownItem)
Vue.use(DropdownMenu)
Vue.use(Loading.directive)
Vue.use(Avatar)
Vue.use(Tag)
Vue.use(Tooltip)
Vue.use(Card)
Vue.use(Divider)
Vue.use(Pagination)
Vue.use(Steps)
Vue.use(Step)
Vue.use(Badge)
Vue.use(Dialog)

Vue.prototype.$loading = Loading.service
Vue.prototype.$message = (option) => Message({ duration: 5000, ...option })
Vue.prototype.$msgbox = MessageBox
Vue.prototype.$alert = MessageBox.alert
Vue.prototype.$confirm = MessageBox.confirm
Vue.prototype.$prompt = MessageBox.prompt
