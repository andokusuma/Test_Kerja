(function ($, window, document) {
  $(document).on("click", ".cancel", function () {
    var editableInstance = $(this)
      .closest(".editable-instance")
      .data("plugin_editable");
    editableInstance.cancel();
  });

  function Editable(element, options) {
    this.element = element;
    this.options = $.extend({}, defaultOptions, options);
    this._defaults = defaultOptions;
    this._name = pluginName;
    this.init();
    $(element).addClass("editable-instance");
  }

  var pluginName = "editable";
  var defaultOptions = {
    keyboard: true,
    dblclick: true,
    button: true,
    buttonSelector: ".edit",
    buttonSelectorCancel: ".cancel",
    maintainWidth: true,
    dropdowns: {},
    edit: function () {},
    save: function () {},
    cancel: function () {},
  };

  Editable.prototype = {
    init: function () {
      this.editing = false;

      if (this.options.button) {
        $(this.options.buttonSelector, this.element).on(
          "click",
          this.toggle.bind(this)
        );
      }
    },
    toggle: function (event) {
      event.preventDefault();
      this.editing = !this.editing;
      this.editing ? this.edit() : this.save();
    },

    edit: function () {
      var _this = this;
      var data = {};

      $("td[data-field]", this.element).each(function () {
        var field, input, value, width, dropdownOptions;

        field = $(this).data("field");
        value = $(this).text();
        width = $(this).width();

        data[field] = value;

        $(this).empty();

        if (_this.options.maintainWidth) {
          $(this).width(width);
        }

        if (field in _this.options.dropdowns) {
          dropdownOptions = _this.options.dropdowns[field];
          input = $("<select></select>");

          for (var i = 0; i < dropdownOptions.length; i++) {
            $("<option></option>").text(dropdownOptions[i]).appendTo(input);
          }

          input
            .val(value)
            .data("old-value", value)
            .on("dblclick", _this._captureEvent);
        } else {
          input = $('<input type="text" />')
            .val(value)
            .data("old-value", value)
            .on("dblclick", _this._captureEvent);
        }

        input.appendTo(this);
      });

      _this.options.edit.bind(_this.element)(data);
    },
    save: function () {
      var _this = this;
      var data = {};

      $("td[data-field]", this.element).each(function () {
        var input = $(":input", this);
        var value = input.val();

        data[$(this).data("field")] = value;

        $(this).empty().text(value);
      });

      _this.options.save.bind(_this.element)(data);

      // Kirim data ke Laravel melalui AJAX
      $.ajax({
        url: "/save-data", // Sesuaikan dengan route yang Anda buat
        method: "POST",
        data: data,
        success: function (response) {
          console.log(response);
          // Tambahkan logika atau tindakan lain setelah berhasil disimpan
        },
        error: function (error) {
          console.error(error);
          // Tambahkan logika atau tindakan lain jika terjadi kesalahan
        },
      });
    },
    cancel: function () {
      var _this = this;
      var data = {};
      $("td[data-field]", this.element).each(function () {
        var input = $(":input", this);
        var oldValue = input.data("old-value");

        data[$(this).data("field")] = oldValue;

        $(this).empty().text(oldValue);
      });

      _this.options.cancel.bind(_this.element)(data);
      // Set this.editing to false
      this.editing = false;
    },
    _captureEvent: function (event) {
      event.stopPropagation();
    },
    _captureKey: function (event) {
      if (this.editing === true) {
        if (event.which === 13) {
          this.save();
          this.editing = false;
        } else if (event.which === 27) {
          this.cancel();
          this.editing = false;
        }
      }
    },
  };

  $.fn[pluginName] = function (options) {
    return this.each(function () {
      var editableInstance =
        $.data(this, "plugin_" + pluginName) ||
        $.data(this, "plugin_" + pluginName, new Editable(this, options));
      $(document).on(
        "keydown",
        $.proxy(editableInstance._captureKey, editableInstance)
      );
    });
  };
})(jQuery, window, document);
