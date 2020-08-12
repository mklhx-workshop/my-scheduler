

function $getTags() {
    let tags = [];
    $.ajax({
        headers: {
            Accept: "application/json"
        },
        url: "/api/tags",
        success: t => {
            $.each(t, (index, tag) => {
                tags[index] = tag;
            });
            console.log("tags from $getTags");
            console.log(tags);
            $.each(tags, (index, tag) => {
                $("#tags").append(
                    $("<option/>", {
                        value: tag.id,
                        text: tag.name
                    })
                );
            });
            return tags;
        }
    });
};

function $getThings() {
    let things = [];
    $.ajax({
        headers: {
            Accept: "application/json"
        },
        url: "/api/things",
        success: t => {
            $.each(t, (index, thing) => {
                things[index] = thing;
            });
            console.log("thing from $getThings");
            console.log(things);
            $.each(things, (index, thing) => {
                $("#things").append(
                    $("<option/>", {
                        value: thing.id,
                        text:
                            thing.brand +
                            " - " +
                            thing.model +
                            " - " +
                            thing.identificationNumber
                    })
                );
            });
            return things;
        }
    });
};

function $getEvents() {
    let events = [];
    $.ajax({
        headers: {
            Accept: "application/json"
        },
        url: "/api/events/",
        success: e => {
            $.each(e, (index, event) => {
                events[index] = event;
            });
            console.log("events from $getEvents");
            console.log(events);
            $.each(events, (index, event) => {
                $("#tag").append(
                    $("<option/>", {
                        value: event.id,
                        text: event.name
                    })
                );
            });
            return events;
        }
    });
};

