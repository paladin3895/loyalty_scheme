<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Bootstrap 101 Template</title>

  <!-- Bootstrap -->
  <script type="text/javascript" src="{{url('bower_components/jquery/dist/jquery.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{url('bower_components/moment/min/moment.min.js')}}"></script> -->
  <!-- <script type="text/javascript" src="{{url('bower_components/moment/min/moment-with-locales.js')}}"></script> -->

  <script type="text/javascript" src="{{url('bootstrap/js/bootstrap.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{url('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script> -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.0/react.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.0/react-dom.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>

  <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}" />
  <!-- <link rel="stylesheet" href="{{url('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" /> -->

  <link href="{{url('font-awesome-4.5.0/css/font-awesome.css')}}" rel="stylesheet">
  <link href="{{url('css/customization.css')}}" rel="stylesheet">

</head>
<body>
  <div id="content"></div>
  <div id="conatiner">
    <!-- Modal -->
  </div>

  <script type="text/babel">
    var SchemaList = React.createClass({
      selectSchema: function(id) {
        this.props.showSchema(id);
      },
      render: function() {
        return (
          <div className="col-sm-offset-2 col-sm-8">
            <table className="table table-hover">
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
                    showSchema={this.selectSchema}
                  />)
                }, this)}
              </tbody>
            </table>
          </div>
        );
      }
    });

    var SchemaRecord = React.createClass({
      selectSchema: function() {
        this.props.showSchema(this.props.schemaId);
      },
      render: function() {
        return (
          <tr>
            <td>{this.props.schemaName}</td>
            <td><a href={this.props.schemaLink}>{this.props.schemaLink}</a></td>
            <td>{this.props.schemaDescription}</td>
            <td>
              <i className="fa fa-play" data-toggle="modal" data-target="#schema-info" data-keyboard="false" data-backdrop="static" onClick={this.selectSchema}></i>
              <i className="fa fa-pencil" data-toggle="modal" data-target="#schema-details" data-keyboard="false" data-backdrop="static"></i>
              <i className="fa fa-trash"></i>
            </td>
          </tr>
        );
      }
    });

    var SchemaInfo = React.createClass({
      render: function() {
        return (
          <div className="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" id="schema-info">
            <div className="modal-dialog modal-lg" role="document">
              <div className="modal-content">
                <div className="modal-header">
                  <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 className="modal-title">Modal title</h4>
                </div>
                <div className="modal-body">
                  <form className="form-horizontal">

                    <input type="hidden" id="schema-id" value={this.props.schemaId}/>

                    <div className="form-group">
                      <label htmlFor="schema-name" className="col-sm-3 control-label">Name</label>
                      <div className="col-sm-6">
                        <input type="text" className="form-control" id="schema-name" placeholder="Name" value={this.props.schemaName}/>
                      </div>
                    </div>

                    <div className="form-group">
                      <label htmlFor="user-link" className="col-sm-3 control-label">User link</label>
                      <div className="col-sm-6">
                        <input type="text" className="form-control" id="user-link" placeholder="User link" value={this.props.schemaLink}/>
                      </div>
                    </div>

                    <div className="form-group">
                      <label  className="col-sm-3 control-label">Description</label>
                      <div className="col-sm-6">
                        <textarea rows="5" className="form-control" placeholder="Description" value={this.props.schemaDescription}/>
                      </div>
                    </div>

                    <div className="form-group">
                      <div className="col-sm-offset-7 col-sm-2">
                        <button type="button" className="btn btn-default yellow"> <i className="fa fa-floppy-o">&nbsp; Save</i></button>
                        <button type="button" className="btn btn-default" data-dismiss="modal">Cancel</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
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
    var SchemaForm = React.createClass({
      render: function() {
        return (
          <div>
            <SchemaInfo
              schemaId={this.props.schemaData.id}
              schemaName={this.props.schemaData.name}
              schemaLink={this.props.schemaData.link}
              schemaDescription={this.props.schemaData.description}
            />
            <SchemaDetail/>
          </div>
        );
      }
    });

    var SchemaManager = React.createClass({
      getInitialState: function() {
        return {
          schemaList: [],
          currentSchema: {}
        };
      },
      componentDidMount: function() {
        this.listSchema();
      },
      listSchema: function() {
        $.ajax({
          url: 'http://liquid.dev/schemas',
          dataType: 'json',
          cache: false,
          success: function(res) {
            this.setState({
              schemaList: res.data
            });
          }.bind(this),
          error: function(xhr, status, err) {
            console.error(status, err.toString());
          }.bind(this)
        });
      },
      createSchema: function(data) {

      },
      showSchema: function(id) {
        this.setState({
          currentSchema: {
            id: 1,
            name: "Come-Stay",
            link: "https://come-stay.vn/policy",
            description: "Testing schema"
          }
        });
      },
      updateSchema: function(data, id) {

      },
      deleteSchema: function(id) {

      },
      render: function() {
        return (
          <div>
            <SchemaList
              schemaList={this.state.schemaList}
              showSchema={this.showSchema}
              deleteSchema={this.deleteSchema}
            />
            <SchemaForm
              schemaData={this.state.currentSchema}
              createSchema={this.createSchema}
              updateSchema={this.updateSchema}
            />
          </div>
        );
      }
    });
    ReactDOM.render(
      <SchemaManager/>,
      document.getElementById('content')
    );
  </script>

  <!-- <script type="text/javascript">
  $(function () {
    var locale = {locale: 'vi'};
    $('#date-from').datetimepicker(locale);
    $('#date-to').datetimepicker(locale);
  });
  </script> -->

</body>
</html>
