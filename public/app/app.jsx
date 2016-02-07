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
          // updateSchemaNode={this.updateSchemaNode}
          // updateSchemaLink={this.updateSchemaLink}
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
          <thead>
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
    console.log(this.props);
    this.props.showSchema(this.props.schemaId, 'info');
  },
  showSchemaDetail: function() {
    this.props.showSchema(this.props.schemaId, 'detail');
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
          <button type="button" className="btn btn-primary" onClick={this.showSchemaDetail}><i className="fa fa-usb"></i></button>
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
      mode: nextProps.formMode,
    });
  },
  updateSchemaInfo: function(data, id) {
    this.props.updateSchemaInfo(data, id);
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
        <SchemaDetail/>
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

var SchemaDetail = React.createClass({
  render: function() {
    return (
      <div className="modal fade bs-example-modal-lg" tabIndex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="schema-details">
        <div className="modal-dialog modal-lg" role="document">
          <div className="modal-content">
            <div className="modal-header">
              <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 className="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div className="modal-body">
              <div className="col-xs-6 left">
                <div className="form-group">
                  <label className="col-sm-4 control-label"><a href="#">Back Schema list</a></label>
                </div>

              </div>

              <div className="col-xs-6 left">
                <ul className="nav nav-tabs" role="tablist">
                  <li role="presentation" className="active"><a href="#policy-tab" aria-controls="policy-tab" role="tab" data-toggle="tab">Policy</a></li>
                  <li role="presentation"><a href="#reward-tab" aria-controls="reward-tab" role="tab" data-toggle="tab">Reward</a></li>
                </ul>

                <div id="myTabContent" className="tab-content">
                  <div role="tabpanel" className="tab-pane fade in active" id="policy-tab" aria-labelledby="policy-tab">
                    <form className="form-horizontal">
                      <div className="form-group">
                        <label htmlFor="category" className="col-sm-2 control-label">Category</label>
                        <div className="col-sm-9">
                          <select className="form-control" id="category" placeholder="Category">
                            <option>Select</option>
                          </select>
                        </div>

                      </div>
                      <div className="form-group">
                        <label htmlFor="policy" className="col-sm-2 control-label">Policy</label>
                        <div className="col-sm-9">
                          <select className="form-control" id="policy" placeholder="Policy">
                            <option>Select</option>
                          </select>
                        </div>

                      </div>
                      <div className="form-group">
                        <div className="col-sm-2 control-label">

                        </div>
                        <div className="col-sm-4">
                          <input type="text" className="form-control" id="key_1" placeholder="Key"/>
                        </div>
                        <div className="col-sm-4">
                          <input type="text" className="form-control" id="value_1" placeholder="Value"/>
                        </div>
                        <div className="col-sm-1">
                          <i className="fa fa-plus-circle fa-2"></i>
                        </div>
                      </div>

                      <div className="form-group right">
                        <div className="col-sm-offset-7 col-sm-6">
                          <button type="button" className="btn btn-default yellow"> <i className="fa fa-plus-circle fa-2">&nbsp; Add</i></button>
                          <div className="button-distance"></div>
                          <button type="button" className="btn btn-default">Reset</button>
                        </div>
                      </div>

                    </form>

                    <div className="col-sm-offset-1 col-sm-2"></div>
                    <table className="table table-hover">
                      <thead>
                        <tr>
                          <th>Level</th>
                          <th>Policy </th>
                          <th>Key/Value</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Level 1</td>
                          <td>Login in <b>5</b> consecutive days </td>
                          <td>n=5</td>
                          <td><i className="fa fa-pencil"></i> <i className="fa fa-trash"></i></td>
                        </tr>
                      </tbody>
                    </table>

                  </div>

                  <div role="tabpanel" className="tab-pane fade" id="reward-tab" aria-labelledby="reward-tab">
                    <form className="form-horizontal">
                      <div className="form-group">
                        <label htmlFor="category-rule" className="col-sm-2 control-label">Category</label>
                        <div className="col-sm-9">
                          <select className="form-control" id="category-rule" placeholder="Category">
                            <option>Select</option>
                          </select>
                        </div>

                      </div>
                      <div className="form-group">
                        <label htmlFor="policy-rule" className="col-sm-2 control-label">Reward</label>
                        <div className="col-sm-9">
                          <select className="form-control" id="policy-rule" placeholder="Policy">
                            <option>Select</option>
                          </select>
                        </div>

                      </div>
                      <div className="form-group">
                        <div className="col-sm-2 control-label">

                        </div>
                        <div className="col-sm-4">
                          <input type="text" className="form-control" id="key" placeholder="Key"/>
                        </div>
                        <div className="col-sm-4">
                          <input type="text" className="form-control" id="value" placeholder="Value"/>
                        </div>
                        <div className="col-sm-1">
                          <i className="fa fa-plus-circle fa-2"></i>
                        </div>

                      </div>

                      <div className="form-group right">
                        <div className="col-sm-offset-7 col-sm-6">
                          <button type="button" className="btn btn-default yellow"> <i className="fa fa-plus-circle fa-2">&nbsp; Add</i></button>
                          <div className="button-distance"></div>
                          <button type="button" className="btn btn-default">Reset</button>
                        </div>

                      </div>
                    </form>

                    <div className="col-sm-offset-1 col-sm-2"></div>
                    <table className="table table-hover">
                      <thead>
                        <tr>
                          <th>Level</th>
                          <th>Reward</th>
                          <th>Key/Value</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Level 1</td>
                          <td>Login in <b>5</b> consecutive days</td>
                          <td>n=5</td>
                          <td><i className="fa fa-pencil"></i> <i className="fa fa-trash"></i></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

ReactDOM.render(
  <SchemaManager/>,
  document.getElementById('container')
);
