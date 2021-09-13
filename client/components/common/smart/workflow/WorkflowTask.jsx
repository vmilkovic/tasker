import { Draggable } from "react-beautiful-dnd";

function WorkflowTask({ task, index, isDragDisabled }) {
  return (
    <Draggable
      draggableId={task.uuid}
      index={index}
      isDragDisabled={isDragDisabled}
    >
      {(provided) => (
        <div
          className="flex-grow p-2 mb-2 border border-gray-300 rounded shadow bg-gray-50"
          {...provided.draggableProps}
          {...provided.dragHandleProps}
          ref={provided.innerRef}
        >
          <h4 className="font-medium">{task.content}</h4>
        </div>
      )}
    </Draggable>
  );
}

export default WorkflowTask;
