var SchemaManager = React.createClass({
  getInitialState: function() {
    return {
      schemaList: [],
      currentSchema: {},
    };
  },
  componentDidMount: function() {
    this.listSchema();
  },
  listSchema: function() {
    $.ajax({
      url: 'http://liquid.dev/schemas',
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          schemaList: res.data,
          formMode: null,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  createSchema: function(data) {
    $.ajax({
      url: 'http://liquid.dev/schemas',
      method: 'POST',
      data: {
        schema: data
      },
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.listSchema();
        // alert('schema ' + res.data.name + ' has been created');
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  showSchema: function(id, mode) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + id,
      method: 'GET',
      data: {
        include: 'nodes,links'
      },
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          currentSchema: res.data,
          formMode: mode
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  updateSchemaInfo: function(data, id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + id,
      method: 'PATCH',
      data: {
        schema: data
      },
      dataType: 'json',
      cache: false,
      success: function(res) {
        // alert('schema ' + res.data.name + ' has been updated');
        this.listSchema();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  deleteSchema: function(id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + id,
      method: 'DELETE',
      dataType: 'json',
      cache: false,
      success: function(res) {
        // alert('schema ' + res.data.name + ' has been deleted');
        this.listSchema();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  prepareNewInstance: function() {
    this.setState({
      currentSchema: {
        id: null,
        name: '',
        link: '',
        description: '',
      },
      formMode: 'info',
    });
  },
  render: function() {
    return (
      <div>
        <SchemaList
          schemaList={this.state.schemaList}
          showSchema={this.showSchema}
          deleteSchema={this.deleteSchema}
          prepareNewInstance={this.prepareNewInstance}
        />
        <SchemaForm
          schemaData={this.state.currentSchema}
          formMode={this.state.formMode}
          createSchema={this.createSchema}
          updateSchemaInfo={this.updateSchemaInfo}
        />
      </div>
    );
  }
});

var SchemaList = React.createClass({
  showSchema: function(id, mode) {
    this.props.showSchema(id, mode);
  },
  deleteSchema: function(id) {
    this.props.deleteSchema(id);
  },
  prepareNewInstance: function() {
    this.props.prepareNewInstance();
    this.forceUpdate();
  },
  render: function() {
    return (
      <div className="col-sm-offset-2 col-sm-8">
        <table className="table table-hover table-bordered table-striped">
          <thead className="thead-inverse">
            <tr>
              <th>Name</th>
              <th>User link</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            {this.props.schemaList.map(function(record) {
              return (<SchemaRecord
                key={record.id}
                schemaId={record.id}
                schemaName={record.name}
                schemaLink={record.link}
                schemaDescription={record.description}
                showSchema={this.showSchema}
                deleteSchema={this.deleteSchema}
              />)
            }, this)}
          </tbody>
        </table>
        <div className="col-sm-2">
          <button type="button" className="btn btn-success" onClick={this.prepareNewInstance}> <i className="fa fa-copy">&nbsp; Create</i></button>
        </div>
      </div>
    );
  }
});

var SchemaRecord = React.createClass({
  showSchemaInfo: function() {
    this.props.showSchema(this.props.schemaId, 'info');
  },
  showSchemaDiagram: function() {
    this.props.showSchema(this.props.schemaId, 'diagram');
  },
  showTestingPanel: function() {
    this.props.showSchema(this.props.schemaId, 'testing');
  },
  deleteSchema: function() {
    var confirm = window.confirm('Are you sure?');
    if (confirm) {
      this.props.deleteSchema(this.props.schemaId);
    }
  },
  render: function() {
    return (
      <tr>
        <td>{this.props.schemaName}</td>
        <td><a href={this.props.schemaLink}>{this.props.schemaLink}</a></td>
        <td>{this.props.schemaDescription}</td>
        <td>
          <button type="button" className="btn btn-info" onClick={this.showSchemaInfo}><i className="fa fa-file-text-o"></i></button>
          <button type="button" className="btn btn-primary" onClick={this.showSchemaDiagram}><i className="fa fa-usb"></i></button>
          <button type="button" className="btn btn-warning" onClick={this.showTestingPanel}><i className="fa fa-paper-plane-o"></i></button>
          <button type="button" className="btn btn-danger" onClick={this.deleteSchema}><i className="fa fa-trash"></i></button>
        </td>
      </tr>
    );
  }
});

var Modal = ReactBootstrap.Modal;

var SchemaForm = React.createClass({
  getInitialState: function() {
    return {};
  },
  // componentDidMount: function() {
  //   this.setState({
  //     schemaId: this.props.schemaData.id,
  //     schemaName: this.props.schemaData.name,
  //     schemaLink: this.props.schemaData.link,
  //     schemaDescription: this.props.schemaData.description,
  //     // schemaNodes: this.props.schemaData.nodes.data,
  //     // schemaLinks: this.props.schemaData.links.data,
  //   });
  // },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      schemaId: nextProps.schemaData.id,
      schemaName: nextProps.schemaData.name,
      schemaLink: nextProps.schemaData.link,
      schemaDescription: nextProps.schemaData.description,
      schemaNodes: nextProps.schemaData.nodes ? nextProps.schemaData.nodes.data : [],
      schemaLinks: nextProps.schemaData.links ? nextProps.schemaData.links.data : [],
      mode: nextProps.formMode,
    });
  },
  updateSchemaInfo: function(data, id) {
    this.props.updateSchemaInfo(data, id);
  },
  updateSchemaDiagram: function(nodes, links, id) {
    this.props.updateSchemaDiagram(nodes, links, id);
  },
  createSchema: function(data) {
    this.props.createSchema(data);
  },
  render: function() {
    return (
      <div>
        <SchemaInfo
          schemaId={this.state.schemaId}
          schemaName={this.state.schemaName}
          schemaLink={this.state.schemaLink}
          schemaDescription={this.state.schemaDescription}
          updateSchemaInfo={this.updateSchemaInfo}
          createSchema={this.createSchema}
          currentMode={this.state.mode == 'info' ? true : false}
        />
        <SchemaDiagram
          schemaId={this.state.schemaId}
          schemaName={this.state.schemaName}
          schemaDescription={this.state.schemaDescription}
          schemaNodes={this.state.schemaNodes}
          schemaLinks={this.state.schemaLinks}
          updateSchemaDiagram={this.updateSchemaDiagram}
          currentMode={this.state.mode == 'diagram' ? true : false}
        />
        <TestingPanel
          schemaId={this.state.schemaId}
          schemaName={this.state.schemaName}
          schemaNodes={this.state.schemaNodes}
          currentMode={this.state.mode == 'testing' ? true : false}
        />
      </div>
    );
  }
});

var SchemaInfo = React.createClass({
  getInitialState: function() {
    return {};
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      schemaName: nextProps.schemaName,
      schemaLink: nextProps.schemaLink,
      schemaDescription: nextProps.schemaDescription,
      visible: nextProps.currentMode ? true : false
    });
  },
  saveSchemaInfo: function() {
    var data = {
      name: this.state.schemaName,
      link: this.state.schemaLink,
      description: this.state.schemaDescription
    };
    if (this.props.schemaId) {
      this.props.updateSchemaInfo(data, this.props.schemaId);
    } else {
      this.props.createSchema(data);
    }
  },
  onNameChange: function(e) {
    this.setState({
      schemaName: e.target.value
    });
  },
  onLinkChange: function(e) {
    this.setState({
      schemaLink: e.target.value
    });
  },
  onDescriptionChange: function(e) {
    this.setState({
      schemaDescription: e.target.value
    });
  },
  hideModal: function() {
    this.setState({ visible: false});
  },
  render: function() {
    return (
      <Modal show={this.state.visible} onHide={this.hideModal}>
      <Modal.Header closeButton>
        <Modal.Title>Modal heading</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <form className="form-horizontal">

          <input type="hidden" id="schema-id" value={this.props.schemaId}/>

          <div className="form-group">
            <label htmlFor="schema-name" className="col-sm-3 control-label">Name</label>
            <div className="col-sm-7">
              <input type="text" className="form-control" id="schema-name" placeholder="Name" value={this.state.schemaName} onChange={this.onNameChange}/>
            </div>
          </div>

          <div className="form-group">
            <label htmlFor="user-link" className="col-sm-3 control-label">User link</label>
            <div className="col-sm-7">
              <input type="text" className="form-control" id="user-link" placeholder="User link" value={this.state.schemaLink} onChange={this.onLinkChange}/>
            </div>
          </div>

          <div className="form-group">
            <label  className="col-sm-3 control-label">Description</label>
            <div className="col-sm-7">
              <textarea rows="5" className="form-control" placeholder="Description" value={this.state.schemaDescription} onChange={this.onDescriptionChange}/>
            </div>
          </div>

        </form>
      </Modal.Body>
      <Modal.Footer>
        <div className="form-group">
          <div className="col-sm-offset-8 col-sm-4">
            <button type="button" className="btn btn-warning" onClick={this.saveSchemaInfo}> <i className="fa fa-floppy-o">&nbsp; Save</i></button>
            <button type="button" className="btn btn-default" onClick={this.hideModal}>Cancel</button>
          </div>
        </div>
      </Modal.Footer>
    </Modal>
    );
  }
});

var SchemaDiagram = React.createClass({
  getInitialState: function() {
    return {
      schemaNodes: [],
      schemaLinks: [],
      diagram: null,
      currentNode: {
        id: null,
        name: null,
        config: {},
        policies: {},
        rewards: {},
      },
      visible: false,
      nodeComponents: {},
      policyComponents: {},
      rewardComponents: {},
    };
  },
  componentDidMount: function() {
    this.listNodeComponents();
    this.listPolicyComponents();
    this.listRewardComponents();
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      visible: nextProps.currentMode ? true : false,
      schemaNodes: nextProps.schemaNodes,
      schemaLinks: nextProps.schemaLinks,
      currentNode: {
        id: null,
        name: null,
        config: {},
        policies: {},
        rewards: {},
      },
    });
  },
  saveSchemaDiagram: function() {

  },
  listNodeComponents: function() {
    $.ajax({
      url: 'http://liquid.dev/processors',
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          nodeComponents: res.data,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  listPolicyComponents: function() {
    $.ajax({
      url: 'http://liquid.dev/policies',
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          policyComponents: res.data,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  listRewardComponents: function() {
    $.ajax({
      url: 'http://liquid.dev/rewards',
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          rewardComponents: res.data,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  listNodes: function() {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/nodes',
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          schemaNodes: res.data,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  showNode: function(id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/node/' + id,
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          currentNode: res.data
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  prepareNewNode: function() {
    this.setState({
      currentNode: {
        id: null,
        name: null,
        config: {},
        policies: {},
        rewards: {},
      },
    })
  },
  createNode: function(data) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/nodes',
      method: 'POST',
      dataType: 'json',
      data: {
        node: data
      },
      cache: false,
      success: function(res) {
        this.listNodes();
        this.listLinks();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  updateNode: function(data, id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/node/' + id,
      method: 'PATCH',
      dataType: 'json',
      data: {
        node: data
      },
      cache: false,
      success: function(res) {
        this.listNodes();
        this.listLinks();
        this.showNode(id);
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  deleteNode: function(id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/node/' + id,
      method: 'DELETE',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.listNodes();
        this.listLinks();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  listLinks: function() {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/links',
      method: 'GET',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.setState({
          schemaLinks: res.data,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  createLink: function(data) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/links',
      method: 'POST',
      dataType: 'json',
      data: {
        link: data
      },
      cache: false,
      success: function(res) {
        this.listLinks();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  updateLink: function(data, id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/link/' + id,
      method: 'PATCH',
      dataType: 'json',
      data: {
        link: data
      },
      cache: false,
      success: function(res) {
        this.listLinks();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  deleteLink: function(id) {
    $.ajax({
      url: 'http://liquid.dev/schema/' + this.props.schemaId + '/link/' + id,
      method: 'DELETE',
      dataType: 'json',
      cache: false,
      success: function(res) {
        this.listLinks();
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(status, err.toString());
      }.bind(this)
    });
  },
  hideModal: function() {
    this.setState({visible: false});
  },
  render: function() {
    return (
      <Modal tabIndex="-1" show={this.state.visible} onHide={this.hideModal} bsSize="large" aria-labelledby="contained-modal-title-lg">
        <Modal.Header closeButton>
          <Modal.Title>Modal heading</Modal.Title>
        </Modal.Header>
        <Modal.Body style={{padding: "0px"}}>
          <div className="col-xs-12" id="diagram"></div>
          <div className="col-xs-6 left">
            <NodeTable
              schemaNodes={this.state.schemaNodes}
              prepareNewNode={this.prepareNewNode}
              showNode={this.showNode}
              deleteNode={this.deleteNode}
            />
          </div>
          <SchemaDiagramForm
            nodeComponents={this.state.nodeComponents}
            policyComponents={this.state.policyComponents}
            rewardComponents={this.state.rewardComponents}
            nodeId={this.state.currentNode.id}
            nodeName={this.state.currentNode.name}
            nodeConfig={this.state.currentNode.config}
            policies={this.state.currentNode.policies}
            rewards={this.state.currentNode.rewards}
            updateNode={this.updateNode}
            createNode={this.createNode}
          />
          <div className="col-xs-12">
            <LinkTable
              schemaNodes={this.state.schemaNodes}
              schemaLinks={this.state.schemaLinks}
              createLink={this.createLink}
              updateLink={this.updateLink}
              deleteLink={this.deleteLink}
            />
          </div>
        </Modal.Body>
        <Modal.Footer>
        </Modal.Footer>
      </Modal>
    );
  }
});

var SchemaDiagramForm = React.createClass({
  getInitialState: function() {
    return {
      nodeName: null,
      policies: {},
      rewards: {},
      nodeConfig: {},
    };
  },
  componentDidMount: function() {
    this.setState({
      nodeName: this.props.nodeName,
      policies: this.props.policies,
      rewards: this.props.rewards,
      nodeConfig: this.props.nodeConfig,
    })
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      nodeName: nextProps.nodeName != null ? nextProps.nodeName : null,
      policies: nextProps.policies != null ? nextProps.policies : {},
      rewards: nextProps.rewards != null ? nextProps.rewards : {},
      nodeConfig: nextProps.nodeConfig != null ? nextProps.nodeConfig : {}
    });
  },
  handleNameChange: function(e) {
    this.setState({
      nodeName: e.target.value
    })
  },
  saveNode: function() {
    var data = {
      name: this.state.nodeName,
      config: this.refs.nodeConfigTab.state.selectedConfig,
      policies: this.refs.policiesTab.state.units,
      rewards: this.refs.rewardsTab.state.units,
    };
    if (this.props.nodeId == null) {
      this.props.createNode(data);
    } else {
      this.props.updateNode(data, this.props.nodeId);
    }
  },
  deleteNode: function() {
    this.props.deleteNode(this.props.nodeId);
  },
  render: function() {
    return (
      <div className="col-xs-6 left">

        <form className="form-horizontal">
          <div className="form-group">
            <label className="col-sm-2 control-label">Name</label>
            <div className="col-sm-9">
              <input type="text" className="form-control" value={this.state.nodeName} onChange={this.handleNameChange} placeholder='Enter node name'/>
            </div>
          </div>
        </form>

        <ul className="nav nav-tabs" role="tablist" style={{marginBottom: "15px"}}>
          <li role="presentation" className="active"><a href="#config-tab" aria-controls="config-tab" role="tab" data-toggle="tab">Config</a></li>
          <li role="presentation"><a href="#policy-tab" aria-controls="policy-tab" role="tab" data-toggle="tab">Policy</a></li>
          <li role="presentation"><a href="#reward-tab" aria-controls="reward-tab" role="tab" data-toggle="tab">Reward</a></li>
        </ul>


        <div id="myTabContent" className="tab-content">
          <div role="tabpanel" className="tab-pane fade in active" id="config-tab" aria-labelledby="config-tab">
            <NodeConfig
              ref="nodeConfigTab"
              nodeComponents={this.props.nodeComponents}
              nodeConfig={this.state.nodeConfig}
            />
          </div>
          <div role="tabpanel" className="tab-pane fade" id="policy-tab" aria-labelledby="policy-tab">
            <NodeUnitComponents
              ref="policiesTab"
              unitComponents={this.props.policyComponents}
              units={this.state.policies}
            />
          </div>
          <div role="tabpanel" className="tab-pane fade" id="reward-tab" aria-labelledby="reward-tab">
            <NodeUnitComponents
              ref="rewardsTab"
              unitComponents={this.props.rewardComponents}
              units={this.state.rewards}
            />
          </div>
        </div>

        <div className="col-xs-12 form-group">
          <button type="button" className="btn btn-danger pull-right" onClick={this.deleteNode}><i className="fa fa-trash"></i></button>
          <button type="button" className="btn btn-warning pull-right" onClick={this.saveNode}><i className="fa fa-floppy-o"></i></button>
        </div>
      </div>
    );
  }
});

var NodeConfig = React.createClass({
  getInitialState: function() {
    return {
      nodeComponents: {},
      selectedConfig: {},
      currentValue: 'default'
    };
  },
  componentDidMount: function() {
    var components = this.props.nodeComponents;
    components['default'] = {};
    this.setState({
      nodeComponents: components,
    });
  },
  componentWillReceiveProps: function(nextProps) {
    var components = this.props.nodeComponents;
    components['default'] = nextProps.nodeConfig;
    this.setState({
      nodeComponents: components,
      selectedConfig: nextProps.nodeConfig ? nextProps.nodeConfig : {},
      currentValue: 'default'
    });
  },
  selectNodeComponent: function(e) {
    this.setState({
      selectedConfig: this.state.nodeComponents[e.target.value],
      currentValue: e.target.value
    });
  },
  handleKeyValueChange: function(e) {
    var config = this.state.selectedConfig;
    config[e.target.dataset.key] = e.target.value;
    this.setState({
      selectedConfig: config
    })
  },
  render: function() {
    return (
      <div>
        <form className="form-horizontal">
          <div className="form-group">
            <label htmlFor="policy" className="col-sm-2 control-label">Node</label>
            <div className="col-sm-9">
              <select className="form-control" value={this.state.currentValue} onChange={this.selectNodeComponent}>
                {Object.keys(this.state.nodeComponents).map(function(type) {
                  var component = this.state.nodeComponents[type];
                  if (type == 'default') {
                    if ((component == null) || (Object.keys(component).length == 0)) {
                      return (
                        <option key={type} disabled value='default'>--Select a node type--</option>
                      );
                    } else {
                      return (
                        <option key={type} value='default'>NodeConfig</option>
                      );
                    }
                  } else {
                    return (
                      <option key={type} value={type}>{type}</option>
                    );
                  }
                }, this)}
              </select>
            </div>

          </div>

          {Object.keys(this.state.selectedConfig).map(function(key) {
            return (
              <div key={"config_" + key} className="form-group">
                <div className="col-sm-offset-2 col-sm-3">
                  <label className="form-control">{key}</label>
                </div>
                <div className="col-sm-6">
                  <input type="text" data-key={key} className="form-control" value={this.state.selectedConfig[key]} readOnly={key == 'class' ? true : false} onChange={this.handleKeyValueChange}/>
                </div>
              </div>
            );
          }, this)}
        </form>
      </div>
    );
  }
});

var NodeUnitComponents = React.createClass({
  getInitialState: function() {
    return {
      units: {},
      selectedUnit: {},
    }
  },
  componentDidMount: function() {
    this.setState({
      units: this.props.units
    })
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      units: nextProps.units
    })
  },
  selectUnit: function(e) {
    var unit = this.props.unitComponents[e.target.value];
    this.setState({
      selectedUnit: unit
    })
  },
  addUnit: function() {
    var units = this.state.units;
    if ((this.state.selectedUnit != null) && (Object.keys(this.state.selectedUnit).length > 0)) {
      units['unit_' + Date.now().toString()] = this.state.selectedUnit;
      this.setState({
        units: units
      })
    }
  },
  removeUnit: function(id) {
    var units = this.state.units;
    delete units[id];
    this.setState({
      units: units
    })
  },
  render: function() {
    return (
      <form className="form-horizontal">
        <div className="form-group">
          <label htmlFor="policy" className="col-sm-2 control-label">Units</label>
          <div className="col-sm-7">
            <select className="form-control" onChange={this.selectUnit} defaultValue='default'>
              <option value='default' disabled>--Select a unit component--</option>
            {Object.keys(this.props.unitComponents).map(function(type) {
              return (
                <option key={type} value={type}>{type}</option>
              );
            }, this)}
            </select>
          </div>
          <div className="col-sm-2">
            <button type="button" className="btn btn-default yellow" onClick={this.addUnit}> <i className="fa fa-plus-circle fa-2">&nbsp; Add</i></button>
          </div>

        </div>
        <div className="col-sm-12 form-group right">
          <div className="panel-group">
           {Object.keys(this.props.units).map(function(id) {
             var units = this.props.units[id];
             return (
               <UnitRecord
                 key={id}
                 recordId={id}
                 recordData={this.props.units[id]}
                 removeUnit={this.removeUnit}
               />
             )
            }, this)}
         </div>
        </div>
      </form>
    );
  }
});

var NodeTable = React.createClass({
  prepareNewNode: function() {
    this.props.prepareNewNode();
  },
  showNode: function(id) {
    this.props.showNode(id);
  },
  deleteNode: function(id) {
    this.props.deleteNode(id);
  },
  render: function() {
    return (
      <div>
        <table className="table table-hover table-bordered">
          <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
            {this.props.schemaNodes.map(function(node) {
              return (
                <NodeRecord
                  key={node.id}
                  nodeId={node.id}
                  nodeName={node.name != null ? node.name : ''}
                  showNode={this.showNode}
                  deleteNode={this.deleteNode}
                />
              )
            }, this)}
          </tbody>
        </table>
        <div className="col-sm-12">
          <button type="button" className="btn btn-success pull-left" onClick={this.prepareNewNode}> <i className="fa fa-dot-circle-o">&nbsp; New</i></button>
        </div>
      </div>
    );
  }
});

var NodeRecord = React.createClass({
  showNode: function() {
    this.props.showNode(this.props.nodeId);
  },
  deleteNode: function() {
    this.props.deleteNode(this.props.nodeId);
  },
  render: function() {
    return (
      <tr>
          <td>{this.props.nodeId}</td>
          <td>{this.props.nodeName}</td>
          <td>
            <button type="button" className="btn btn-info" onClick={this.showNode}><i className="fa fa-file-text-o"></i></button>
            <button type="button" className="btn btn-danger" onClick={this.deleteNode}><i className="fa fa-trash"></i></button>
          </td>
      </tr>
    );
  }
});

var UnitRecord = React.createClass({
  getInitialState: function() {
    return {
      recordData: {}
    };
  },
  componentDidMount: function() {
    this.setState({
      recordData: this.props.recordData
    });
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      recordData: nextProps.recordData
    });
  },
  handleKeyValueChange: function(e) {
    var recordData = this.state.recordData;
    recordData[e.target.dataset.key] = e.target.value;
    this.setState({
      recordData: recordData
    })
  },
  removeUnit: function() {
    this.props.removeUnit(this.props.recordId);
  },
  render: function() {
    return (<div className="panel panel-default">
      <div className="panel-heading">
        <h4 className="panel-title">
          <a data-toggle="collapse" href={"#policy_" + this.props.recordId}>{this.state.recordData.class}</a>
          <span className="label label-danger hover pull-right" data-id={this.props.recordId} onClick={this.removeUnit}><i className="fa fa-trash"></i></span>
        </h4>
      </div>
      <div id={"policy_" + this.props.recordId} className="panel-collapse collapse">
        <div className="panel-body">
        {
          Object.keys(this.state.recordData).map(function(key) {
            if (key == 'class') return;
            var data = this.state.recordData;
            return(
              <div key={this.props.recordId + "_" + key} className="row">
                <div className="col-sm-3">
                <label className="form-control">{key}</label>
                </div>
                <div className="col-sm-9">
                <input type="text" className="form-control" placeholder="Value" data-key={key} value={data[key]} onChange={this.handleKeyValueChange}/>
                </div>
              </div>
            );
          }, this)
        }
        </div>
      </div>
    </div>);
  }
});

var LinkTable = React.createClass({
  getInitialState: function() {
    return {
      newLink: {
        node_from: null,
        node_to: null,
      }
    }
  },
  changeNewLinkFrom: function(e) {
    var link = this.state.newLink;
    link.node_from = e.target.value;
    this.setState({
      newLink: link
    })
  },
  changeNewLinkTo: function(e) {
    var link = this.state.newLink;
    link.node_to = e.target.value;
    this.setState({
      newLink: link
    })
  },
  createLink: function() {
    var link = this.state.newLink;
    if ((link.node_from != null) && (link.node_to != null) && (link.node_from != link.node_to)) {
      this.props.createLink(link);
    } else {
      alert('invalid nodes to link');
    }
  },
  updateLink: function(data, id) {
    this.props.updateLink(data, id);
  },
  deleteLink: function(id) {
    this.props.deleteLink(id);
  },
  render: function() {
    return (
      <div>
        <table className="table table-hover table-bordered">
          <thead>
            <tr>
                <th>ID</th>
                <th>Config</th>
                <th>From Node</th>
                <th>To Node</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
            {this.props.schemaLinks.map(function(link) {
              return (
                <LinkRecord
                  key={link.id}
                  linkId={link.id}
                  linkFrom={link.node_from}
                  linkTo={link.node_to}
                  linkConfig={link.config != null ? link.config : {}}
                  schemaNodes={this.props.schemaNodes}
                  createLink={this.createLink}
                  updateLink={this.updateLink}
                  deleteLink={this.deleteLink}
                />
              )
            }, this)}
            <tr>
                <td>#</td>
                <td>###</td>
                <td>
                  <select className="form-control" defaultValue='default' onChange={this.changeNewLinkFrom}>
                    <option value="default" disabled>--Select a node--</option>
                    {this.props.schemaNodes.map(function(node) {
                      return (
                        <option key={"link_from_node_" + node.id} value={node.id}>{node.name + ' (#' + node.id + ')'}</option>
                      )
                    })}
                  </select>
                </td>
                <td>
                  <select className="form-control" defaultValue='default' onChange={this.changeNewLinkTo}>
                    <option value="default" disabled>--Select a node--</option>
                    {this.props.schemaNodes.map(function(node) {
                      return (
                        <option key={"link_to_node_" + node.id} value={node.id}>{node.name + ' (#' + node.id + ')'}</option>
                      )
                    })}
                  </select>
                </td>
                <td>
                  <button type="button" className="btn btn-success pull-right" onClick={this.createLink}><i className="fa fa-arrows-h">&nbsp; New</i></button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    );
  }
});

var LinkRecord = React.createClass({
  getInitialState: function() {
    return {
      linkFrom: null,
      linkTo: null,
    }
  },
  componentDidMount: function() {
    this.setState({
      linkFrom: this.props.linkFrom,
      linkTo: this.props.linkTo
    })
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      linkFrom: nextProps.linkFrom,
      linkTo: nextProps.linkTo
    })
  },
  changeLinkFrom: function(e) {
    this.setState({
      linkFrom: e.target.value
    })
  },
  changeLinkTo: function(e) {
    this.setState({
      linkTo: e.target.value
    })
  },
  saveLink: function() {
    if (this.props.linkId != null) {
      this.props.updateLink({
        node_from: this.state.linkFrom,
        node_to: this.state.linkTo,
        config: this.props.linkConfig
      }, this.props.linkId)
    } else {
      this.props.createLink({
        node_from: this.state.linkFrom,
        node_to: this.state.linkTo,
        config: this.props.linkConfig
      })
    }
  },
  deleteLink: function() {
    this.props.deleteLink(this.props.linkId);
  },
  render: function() {
    return (
      <tr>
          <td>{this.props.linkId}</td>
          <td>###</td>
          <td>
            <select className="form-control" defaultValue={this.props.linkFrom} onChange={this.changeLinkFrom}>
              {this.props.schemaNodes.map(function(node) {
                return (
                  <option key={"link_from_node_" + node.id} value={node.id}>{node.name + ' (#' + node.id + ')'}</option>
                )
              })}
            </select>
          </td>
          <td>
            <select className="form-control" defaultValue={this.props.linkTo} onChange={this.changeLinkTo}>
              {this.props.schemaNodes.map(function(node) {
                return (
                  <option key={"link_to_node_" + node.id} value={node.id}>{node.name + ' (#' + node.id + ')'}</option>
                )
              })}
            </select>
          </td>
          <td>
            <button type="button" className="btn btn-danger pull-right" onClick={this.deleteLink}><i className="fa fa-trash"></i></button>
            <button type="button" className="btn btn-warning pull-right" onClick={this.saveLink}><i className="fa fa-floppy-o"></i></button>
          </td>
      </tr>
    )
  }
});

var TestingPanel = React.createClass({
  getInitialState: function() {
    return {
      visible: false
    };
  },
  componentWillReceiveProps: function(nextProps) {
    this.setState({
      visible: nextProps.currentMode
    });
  },
  selectNode: function() {

  },
  submitTest: function() {

  },
  hideModal: function() {
    this.setState({
      visible: false
    })
  },
  render: function() {
    return (
      <Modal show={this.state.visible} onHide={this.hideModal} bsSize="large">
        <Modal.Header closeButton>
          <Modal.Title>Testing Panel</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <div className="row">
            <div className="col-sm-6">
              <form className="form-horizontal">
                <div className="well">
                  <label>Data</label>
                  <textarea className="form-control" rows="5" placeholder="json data for testing"/>
                </div>
              </form>
            </div>
            <div className="col-sm-6">
              <CheckpointForm/>
            </div>
          </div>
        </Modal.Body>
        <Modal.Footer>
          <div className="form-group">
            <div className="col-sm-12">
              <button type="button" className="btn btn-default pull-right" onClick={this.hideModal}>Cancel</button>
              <button type="button" className="btn btn-primary pull-right" onClick={this.submitTest}> <i className="fa fa-paper-plane-o">&nbsp; Submit</i></button>
            </div>
          </div>
        </Modal.Footer>
      </Modal>
    );
  }
})

var CheckpointForm = React.createClass({
  render: function() {
    return (
      <form className="form-horizontal">
        <div className="form-group">
          <label htmlFor="policy" className="col-sm-2 control-label">Checkpoint</label>
          <div className="col-sm-7">
            <select className="form-control" onChange={this.selectNode} defaultValue='default'>
              <option value='default' disabled>--Select a node to create checkpoint--</option>
            </select>
          </div>
          <div className="col-sm-2">
            <button type="button" className="btn btn-default yellow" onClick={this.addCheckpoint}> <i className="fa fa-plus-circle fa-2">&nbsp; Add</i></button>
          </div>
        </div>
        <div className="col-sm-12 form-group right">
          <CheckpointRecord/>
        </div>
      </form>
    )
  }
})

var CheckpointRecord = React.createClass({
  render: function() {
    return (
      <div className="panel-group">
        <div className="panel panel-default">
          <div className="panel-heading">
            <h4 className="panel-title">
              <a data-toggle="collapse" href="#node_id">Checkpoint</a>
              <span className="label label-danger hover pull-right" onClick={this.removeNode}><i className="fa fa-trash"></i></span>
            </h4>
          </div>
          <div id="node_id" className="panel-collapse collapse">
            <div className="panel-body">
              <div key="key_1" className="row">
                <div className="col-sm-6">
                <label className="form-control">Node has been passed?</label>
                </div>
                <div className="col-sm-6">
                  <div className="btn-group" data-toggle="buttons">
                    <label className="btn btn-default active">
                      <input type="radio" name="options" value="Yes"/>
                      Yes
                    </label>
                    <label className="btn btn-default">
                      <input type="radio" name="options" value="No"/>
                      No
                    </label>
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col-xs-6 left">
                  <div className="well">
                    <label>Last Memory</label>
                    <textarea className="form-control" placeholder="json data for memory"/>
                  </div>
                </div>
                <div className="col-xs-6 right">
                  <div className="well">
                    <label>Last Result</label>
                    <textarea className="form-control" placeholder="json data for result"/>
                  </div>
              </div>
              </div>
            </div>
          </div>
        </div>
     </div>
    )
  }
})

ReactDOM.render(
  <SchemaManager/>,
  document.getElementById('container')
);
