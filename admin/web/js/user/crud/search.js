var bbCallbacks = {
    changeButton: function(data, el) {
        if (data.success) {
            $(el).replaceWith(data.html)
        }
    }
};
