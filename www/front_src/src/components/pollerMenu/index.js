import React, { Component } from "react";
import PropTypes from 'prop-types';
import classnames from 'classnames';
import styles from '../header/header.scss';
import config from "../../config";
import { Translate, I18n } from 'react-redux-i18n';
import axios from "../../axios";
import { Link } from "react-router-dom";

import { connect } from "react-redux";

const POLLER_CONFIGURATION_TOPOLOGY_PAGE = '60901'

const getIssueClass = (issues, key) => {
  return (issues && issues.length != 0) ?
  ((issues[key]) ?
    ((issues[key].warning) ?
      'orange'
      : ((issues[key].critical)
        ? 'red'
        : 'green'))
    : 'green')
  : 'green';
}

const getPollerStatusIcon = issues => {

  let databaseClass = getIssueClass(issues, 'database');

  let latencyClass = getIssueClass(issues, 'latency');

  return (
    <>
      <span className={classnames(styles["wrap-left-icon"], styles["round"], styles[databaseClass])}>
        <span
          className={classnames(styles["iconmoon"], styles["icon-database"])}
          title={databaseClass === 'green' ?
             I18n.t('OK: all database poller updates are active') :
             I18n.t('Some database poller updates are not active; check your configuration')
          }
        />
      </span>
      <span className={classnames(styles["wrap-left-icon"], styles["round"], styles[latencyClass])}>
        <span
          className={classnames(styles["iconmoon"], styles["icon-clock"])}
          title={latencyClass == 'green' ?
             I18n.t('OK: no latency detected on your platform') :
             I18n.t('Latency detected, check configuration for better optimization')
          }
        />
      </span>
    </>
  );
};

class PollerMenu extends Component {

  pollerService = axios(
    "internal.php?object=centreon_topcounter&action=pollersListIssues"
  );

  refreshInterval = null;

  state = {
    toggled: false,
    data: null,
    intervalApplied: false
  };

  UNSAFE_componentWillMount() {
    window.addEventListener('mousedown', this.handleClick, false);
  };

  componentWillUnmount() {
    window.removeEventListener('mousedown', this.handleClick, false);
    clearInterval(this.refreshInterval);
  };

  // fetch api to get host data
  getData = () => {
    this.pollerService.get().then(({data}) => {
      this.setState({
        data
      });
    }).catch((error) => {
      if (error.response.status == 401){
        this.setState({
          data: null
        });
      }
    });
  }

  componentWillReceiveProps = (nextProps) => {
    const {refreshTime} = nextProps;
    const {intervalApplied} = this.state;
    if(refreshTime && !intervalApplied){
      this.getData();
      this.refreshInterval = setInterval(() => {
        this.getData();
      }, refreshTime);
      this.setState({
        intervalApplied:true
      })
    }
  }

  // display/hide detailed poller data
  toggle = () => {
    const { toggled } = this.state;
    this.setState({
      toggled: !toggled
    });
  };

  // hide poller detailed data if click outside
  handleClick = (e) => {
    if (!this.poller || this.poller.contains(e.target)) {
      return;
    }
    this.setState({
      toggled: false
    });
  };

  render() {
    const { data, toggled } = this.state;

    if (!data) {
      return null;
    }

    // check if poller configuration page is allowed
    const { entries } = this.props.navigationData;
    const allowPollerConfiguration = entries.includes(POLLER_CONFIGURATION_TOPOLOGY_PAGE)

    const statusIcon = getPollerStatusIcon(data.issues);

    return (
      <div className={classnames(styles["wrap-left-pollers"], {[styles["submenu-active"]]: toggled})}>
        {statusIcon}
        <div ref={poller => this.poller = poller}>
          <span className={classnames(styles["wrap-left-icon"], styles["pollers"])} onClick={this.toggle}>
            <span className={classnames(styles["iconmoon"], styles["icon-poller"])} />
            <span className={styles["wrap-left-icon__name"]}><Translate value="Pollers"/></span>
          </span>
          <span className={styles["toggle-submenu-arrow"]} onClick={this.toggle} >{this.props.children}</span>
          <div className={classnames(styles["submenu"], styles["pollers"])}>
            <div className={styles["submenu-inner"]}>
              <ul className={classnames(styles["submenu-items"], styles["list-unstyled"])}>
                <li className={styles["submenu-item"]}>
                  <span className={styles["submenu-item-link"]}>
                    <Translate value="All pollers"/>
                    <span className={styles["submenu-count"]}>{data.total ? data.total : "..."}</span>
                  </span>
                </li>
                {data.issues
                  ? Object.entries(data.issues).map(([key, issue]) => {
                    let message = "";

                    if (key === "database") {
                      message = I18n.t("Database updates not active");
                    } else if (key === "stability") {
                      message = I18n.t("Pollers not running");
                    } else if (key === "latency") {
                      message = I18n.t("Latency detected");
                    }

                    return (
                      <li className={styles["submenu-top-item"]}>
                        <span className={styles["submenu-top-item-link"]}>
                          {message}
                          <span className={styles["submenu-top-count"]}>
                            {issue.total ? issue.total : "..."}
                          </span>
                        </span>
                        {Object.entries(issue).map(([elem, values]) => {
                          if (values.poller) {
                            const pollers = values.poller;
                            return pollers.map((poller) => {
                              let color = 'red';
                              if (elem === 'warning') {
                                color = 'orange';
                              }
                              return (
                                <span
                                  className={styles["submenu-top-item-link"]}
                                  style={{ padding: "0px 16px 17px" }}
                                >
                                  <span className={classnames(styles["dot-colored"], styles[color])}>
                                    {poller.name}
                                  </span>
                                </span>
                              );
                            });
                          } else return null;
                        })}
                      </li>
                    );
                  })
                : null}
                {allowPollerConfiguration && /* display poller configuration button if user is allowed */
                  <Link to={"/main.php?p=" + POLLER_CONFIGURATION_TOPOLOGY_PAGE}>
                    <button
                      onClick={this.toggle}
                      className={classnames(
                        styles["btn"],
                        styles["btn-big"],
                        styles["btn-green"],
                        styles["submenu-top-button"]
                      )}
                    >
                      <Translate value="Configure pollers"/>
                    </button>
                  </Link>
                }
              </ul>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

const mapStateToProps = ({ navigation, intervals }) => ({
  navigationData: navigation,
  refreshTime: intervals ? parseInt(intervals.AjaxTimeReloadStatistic)*1000 : false
});

const mapDispatchToProps = {};

export default connect(mapStateToProps, mapDispatchToProps)(PollerMenu);

PollerMenu.propTypes = {
  children: PropTypes.element.isRequired,
};

