import * as actions from "../actions/bamConfigurationActions";

const initialState = {
    id: null,
    activate: false,
    name: null,
    description: null,
    icon: null,
    inherit_kpi_downtimes: false,
    additional_poller: [],
    groups: [],
    notifications_enabled: false,
    bam_contact: [],
    notification_period: null,
    notification_interval: null,
    notification_options: [],
    level_w: null,
    level_c: null,
    reporting_timeperiods: [],
    sla_month_percent_warn: null,
    sla_month_percent_crit: null,
    sla_month_duration_warn: null,
    sla_month_duration_crit: null,
    bam_esc: [],
    event_handler_enabled: false,
    event_handler_command: null,
    event_handler_args: null
  };

const bamConfigurationReducer = (state = initialState, action) => {
  switch (action.type) {
    case actions.SET_BA_CONFIGURATION:
      return { ...state, ...action.configuration };
    default:
      return state;
  }
};

export default bamConfigurationReducer;
