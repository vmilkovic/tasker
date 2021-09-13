import { Draggable, Droppable } from "react-beautiful-dnd";
import WorkflowTask from "./WorkflowTask";

function Workflow({ workflow, tasks, index, isDropDisabled }) {
  return (
    <Draggable draggableId={workflow.uuid} index={index}>
      {(provided) => (
        <div
          className="flex flex-col w-56 m-2 bg-white border border-gray-200 rounded shadow h-96"
          {...provided.draggableProps}
          ref={provided.innerRef}
        >
          <h3 className="p-2 font-medium" {...provided.dragHandleProps}>
            {workflow.title}
          </h3>
          <Droppable
            droppableId={workflow.uuid}
            isDropDisabled={isDropDisabled}
            type="tasks"
          >
            {(provided) => (
              <div
                className="min-h-full p-2"
                ref={provided.innerRef}
                {...provided.droppableProps}
              >
                {tasks.map((task, index) => (
                  <WorkflowTask key={task.uuid} task={task} index={index} />
                ))}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
        </div>
      )}
    </Draggable>
  );
}

export default Workflow;
